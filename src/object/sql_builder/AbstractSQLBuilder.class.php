<?php
/**
* base class for SQL builder
*
* PHP version 5
*
* @package    objects.sql_builders
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
abstract class Charcoal_AbstractSQLBuilder extends Charcoal_CharcoalObject implements Charcoal_ISQLBuilder
{
	private $_type_mapping;

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$this->_type_mapping = $config->getArray( 'type_mappings' );
	}

	/*
	 * タイプマッピング
	 */
	public function mapType( $type_name )
	{
		return isset($this->_type_mapping[$type_name]) ? $this->_type_mapping[$type_name] : $type_name;
	}

	/**
	 *	Generate RDBMS-specific SQL for SELECT
	 *	
	 *	@param Charcoal_ITableModel $model        table model object related with th query
	 *	@param string $alias                      table model alias which is specified by $model
	 *	@param int $options                       options for SQL generation
	 *	@param Charcoal_SQLCriteria $criteria     criteria which should be used in WHERE clause
	 *	@param array $joins                       list of join(list of Charcoal_QueryJoin object)
	 *	@param array $fields                      list of fields which will be returned in query result
	 *	
	 *	@return string                            SQL
	 */
	public  function buildSelectSQL( $model, $alias, $options, $criteria, $joins, $fields = NULL )
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_ITableModel', $model );
		Charcoal_ParamTrait::checkString( 2, $alias, TRUE );
		Charcoal_ParamTrait::checkInteger( 3, $options );
		Charcoal_ParamTrait::checkIsA( 4, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::checkVector( 5, $joins );
		Charcoal_ParamTrait::checkVector( 6, $fields, NULL );

		$options = ui($options);
		$alias = us($alias);

		$table = $model->getTableName();

		$fields = v($fields)->join(",");

		if ( Charcoal_System::isBitSet( $options, Charcoal_EnumQueryOption::DISTINCT ) ){
			$sql = "SELECT DISTINCT {$fields} FROM " . us($table);
		}
		else{
			$sql = "SELECT {$fields} FROM " . us($table);
		}
	
		if ( !empty($alias) ){
			$sql .= ' AS ' . $alias;
		}

		foreach( $joins as $join ){
			$join_type  = $join->getJoinType();
			$join_model = $join->getModelName();
			$join_alias = $join->getAlias();
			$join_cond  = $join->getCondition();

			switch( $join_type ){
			case Charcoal_EnumSQLJoinType::INNER_JOIN:
				$sql .= ' INNER JOIN ' . $join_model;
				break;
			case Charcoal_EnumSQLJoinType::LEFT_JOIN:
				$sql .= ' LEFT JOIN ' . $join_model;
				break;
			case Charcoal_EnumSQLJoinType::RIGHT_JOIN:
				$sql .= ' RIGHT JOIN ' . $join_model;
				break;
			}

			if ( $join_alias && !$join_alias->isEmpty() ){
				$sql .= ' AS ' . $join_alias;
			}

			if ( $join_cond && !$join_cond->isEmpty() ){
				$sql .= ' ON ' . $join_cond;
			}
		}

		$where_clause = $criteria->getWhere();
		$order_by     = $criteria->getOrderBy();
		$limit        = $criteria->getLimit();
		$offset       = $criteria->getOffset();
		$group_by     = $criteria->getGroupBy();

		if ( !empty($where_clause) ){
			$sql .= ' WHERE ' . $where_clause;
		}
		if ( !empty($order_by) ){
			$sql .= ' ORDER BY ' . $order_by;
		}
		if ( !empty($limit) ){
			$sql .= ' LIMIT ' . $limit;
		}
		if ( !empty($offset) ){
			$sql .= ' OFFSET ' . $offset;
		}
		if ( !empty($group_by) ){
			$sql .= ' GROUP BY ' . $group_by;
		}

		if ( Charcoal_System::isBitSet( $options, Charcoal_EnumQueryOption::FOR_UPDATE ) ){
			$sql .= " FOR UPDATE";
		}
	
		return $sql;
	}

	/**
	 *	Generate RDBMS-specific SQL for UPDATE
	 *	
	 *	@param Charcoal_ITableModel $model        table model object related with th query
	 *	@param string $alias                      table model alias which is specified by $model
	 *	@param Charcoal_DTO $dto                  DTO object which includes the fields to update
	 *	@param Charcoal_SQLCriteria $criteria     criteria which should be used in WHERE clause
	 *	@param array $override                    association field set which you want to override
	 *	
	 *	@return array                             the first element means SQL, the second element means parameter values
	 */
	public  function buildUpdateSQL( $model, $alias, $dto, $criteria, $override = NULL )
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_ITableModel', $model );
		Charcoal_ParamTrait::checkString( 2, $alias, TRUE );
		Charcoal_ParamTrait::checkIsA( 3, 'Charcoal_DTO', $dto );
		Charcoal_ParamTrait::checkIsA( 4, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::checkHashMap( 5, $override, TRUE );

		try{
			$SQL_params      = array();
			$SQL_set         = array();
			$SQL_key_fields  = array();

			$override = up($override);

			$field_list = $model->getFieldList();
//			log_debug( "debug, smart_gateway", 'sql_builder', "field_list:" . print_r($field_list,true) );

			foreach( $field_list as $field ) 
			{
				$update = $model->getAnnotationValue( s($field), s('update') );

				if ( $override && isset($override[$field]) ){
					$update = isset($override[$field]['update']) ? $override[$field]['update'] : $update;
				}
//				log_debug( "debug, smart_gateway", 'sql_builder', "override:" . print_r($override,true) );

				if ( !$update ){
					// 無指定の場合エラー
					_throw( new Charcoal_TableModelFieldException( $model, $field, '[@update] annotation is required' ) );
				}

				// キーフィールドのチェック
				$pk = $model->getAnnotationValue( s($field), s('pk') );
				if ( $pk ){
					// キーフィールドに追加
					$SQL_key_fields[$field] = $dto->$field;
					// キーフィールドなので更新しない
					continue;
				}

				// @updateアノテーションの値によって分岐
				$update_anno = us($update->getValue());
//				log_debug( "debug, smart_gateway", 'sql_builder', "[$name] update_anno:" . print_r($update_anno,true) );
				switch ( $update_anno ){
				case 'value':
					// 値で更新
					$value = $dto->$field;
					if ( $value !== NULL ){
						$SQL_set[] = $field . ' = ?';
						// パラメータを追加
						$SQL_params[] = $value;
					}
					break;
				case 'function':
					// 関数を決定
					$params = uv( $update->getParameters() );
					if ( count($params) == 1 ){
						switch( $params[0] ){
						case 'now':		$function = 'NOW()';	break;
						default:        $function = 'NULL';		break;
						}
					}
					// 関数で更新
					$SQL_set[] = $field . ' = ' . $function;
					break;
				case 'no':
					// 更新しない
					break;
				default:
					_throw( new Charcoal_TableModelFieldException( $model, $field, '[@update] value is invalid' ) );
				}
			}

			// WHERE句
			if ( $criteria ){
				$SQL_WHERE  = us( $criteria->getWhere() );
				$SQL_params = array_merge( $SQL_params, uv($criteria->getParams()) );
			}
			else{
				$SQL_WHERE = array();
				foreach( $SQL_key_fields as $field => $value )
				{
					// ステートメントを追加
					$SQL_WHERE[] = $field . ' = ?';
					// パラメータを追加
					$SQL_params[] = $value;
				}

				$SQL_WHERE  = implode( ' and ', $SQL_WHERE );
			}

			$SQL_set    = implode( ',', $SQL_set );
			$table      = $model->getTableName();

			$sql = "update " . us($table) . " set " . us($SQL_set) . " WHERE " . us($SQL_WHERE);

			return array( $sql, $SQL_params );
		}
		catch ( Exception $e )
		{
			_throw( new Charcoal_SQLBuilderException( "DefaultSQLBuilder#buildUpdateSQL failed" ) );
		}
	}

	/**
	 *	Generate RDBMS-specific SQL for INSERT
	 *	
	 *	@param Charcoal_ITableModel $model        table model object related with th query
	 *	@param string $alias                      table model alias which is specified by $model
	 *	@param Charcoal_DTO $dto                  DTO object which includes the fields to insert
	 *	@param array $override                    association field set which you want to override
	 *	
	 *	@return array                             the first element means SQL, the second element means parameter values
	 */
	public  function buildInsertSQL( $model, $alias, $dto, $override = NULL )
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_ITableModel', $model );
		Charcoal_ParamTrait::checkString( 2, $alias, TRUE );
		Charcoal_ParamTrait::checkIsA( 3, 'Charcoal_DTO', $dto );
		Charcoal_ParamTrait::checkHashMap( 4, $override, TRUE );

		try{
			$SQL_field_list   = NULL;
			$SQL_value_list   = NULL;
			$SQL_params       = array();

			$override = up($override);

			$field_list = $model->getFieldList();

			foreach( $field_list as $field ) 
			{
				$insert = $model->getAnnotationValue( s($field), s('insert') );

				if ( $override && isset($override[$field]) ){
					$insert = isset($override[$name]['insert']) ? $override[$field]['insert'] : $insert;
				}

				if ( !$insert ){
					// 無指定の場合エラー
					_throw( new Charcoal_TableModelFieldException( $model, $field, '[@insert] annotation is required' ) );
				}

				// @insertアノテーションの値によって分岐
				$insert_anno = us($insert->getValue());
				switch ( $insert_anno ){
				case 'value':
					// 値で更新
					$value = $dto->$field;
					if ( $value !== NULL ){
						$SQL_field_list[] = $field;
						$SQL_value_list[] = '?';
						// パラメータを追加
						$SQL_params[] = $value;
					}
					break;
				case 'function':
					// 関数を決定
					$params = uv( $insert->getParameters() );
					if ( count($params) == 1 ){
						switch( $params[0] ){
						case 'now':		$function = 'NOW()';	break;
						default:        $function = 'NULL';		break;
						}
					}
					// 関数で更新
					$SQL_field_list[] = $field;
					$SQL_value_list[] = $function;
					break;
				case 'no':
					// 更新しない
					break;
				default:
					_throw( new Charcoal_TableModelFieldException( $model, $field, '[@insert] value is invalid' ) );
				}
			}

			$SQL_field_list = implode( ',', $SQL_field_list );
			$SQL_value_list = implode( ',', $SQL_value_list );

			$table = $model->getTableName();

			$sql = "insert into " . us($table) . "(" . us($SQL_field_list) . ") values(" . us($SQL_value_list) . ")";

			return array( $sql, $SQL_params );
		}
		catch ( Exception $e )
		{
			_throw( new Charcoal_SQLBuilderException( "DefaultSQLBuilder#buildInsertSQL failed" ) );
		}
	}

	/**
	 *	Generate RDBMS-specific SQL for MIN/MAX/SUM/COUNT/AVG
	 *	
	 *	@param Charcoal_ITableModel $model        table model object related with th query
	 *	@param string $alias                      table model alias which is specified by $model
	 *	@param int $aggregate_func                specify aggregate function which is defined in Charcoal_EnumSQLAggregateFunc
	 *	@param Charcoal_SQLCriteria $criteria     criteria which should be used in WHERE clause
	 *	@param array $joins                       list of join(list of Charcoal_QueryJoin object)
	 *	@param array $fields                      list of fields which will be returned in query result
	 *	
	 *	@return string                            SQL
	 */
	public  function buildAggregateSQL( $model, $alias, $aggregate_func, $criteria, $joins, $fields = NULL )
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_ITableModel', $model );
		Charcoal_ParamTrait::checkString( 2, $alias, TRUE );
		Charcoal_ParamTrait::checkInteger( 3, $aggregate_func );
		Charcoal_ParamTrait::checkIsA( 4, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::checkVector( 5, $joins );
		Charcoal_ParamTrait::checkVector( 6, $fields, NULL );

		$table_name = $model->getTableName();

		$aggregate_func = ui($aggregate_func);

		$aggregate_func_map = array(
				Charcoal_EnumSQLAggregateFunc::FUNC_MIN => 'MIN',
				Charcoal_EnumSQLAggregateFunc::FUNC_MAX => 'MAX',
				Charcoal_EnumSQLAggregateFunc::FUNC_SUM => 'SUM',
				Charcoal_EnumSQLAggregateFunc::FUNC_COUNT => 'COUNT',
				Charcoal_EnumSQLAggregateFunc::FUNC_AVG => 'AVG',
			);

		$func = $aggregate_func_map[$aggregate_func];

		$fields = v($fields)->join(",");

		$sql = "SELECT $func($fields) FROM " . us($table_name);

		if ( !empty($alias) ){
			$sql .= ' AS ' . $alias;
		}

		foreach( $joins as $join ){
			$join_type  = $join->getJoinType();
			$join_model = $join->getModelName();
			$join_alias = $join->getAlias();
			$join_cond  = $join->getCondition();

			switch( $join_type ){
			case Charcoal_EnumSQLJoinType::INNER_JOIN:
				$sql .= ' INNER JOIN ' . $join_model;
				break;
			case Charcoal_EnumSQLJoinType::LEFT_JOIN:
				$sql .= ' LEFT JOIN ' . $join_model;
				break;
			case Charcoal_EnumSQLJoinType::RIGHT_JOIN:
				$sql .= ' RIGHT JOIN ' . $join_model;
				break;
			}

			if ( $join_alias && !$join_alias->isEmpty() ){
				$sql .= ' AS ' . $join_alias;
			}

			if ( $join_cond && !$join_cond->isEmpty() ){
				$sql .= ' ON ' . $join_cond;
			}
		}

		$where_clause = $criteria->getWhere();
		$limit        = $criteria->getLimit();
		$offset       = $criteria->getOffset();
		$group_by     = $criteria->getGroupBy();

		if ( !empty($where_clause) ){
			$sql .= ' WHERE ' . $where_clause;
		}
		if ( !empty($limit) ){
			$sql .= ' LIMIT ' . $limit;
		}
		if ( !empty($offset) ){
			$sql .= ' OFFSET ' . $offset;
		}
		if ( !empty($group_by) ){
			$sql .= ' GROUP BY ' . $group_by;
		}

		return $sql;
	}

	/**
	 *	Generate RDBMS-specific SQL for DELETE
	 *	
	 *	@param Charcoal_ITableModel $model        table model object related with th query
	 *	@param string $alias                      table model alias which is specified by $model
	 *	@param Charcoal_SQLCriteria $criteria     criteria which should be used in WHERE clause
	 *	
	 *	@return string                            SQL
	 */
	public  function buildDeleteSQL( $model, $alias, $criteria )
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_ITableModel', $model );
		Charcoal_ParamTrait::checkString( 2, $alias );
		Charcoal_ParamTrait::checkIsA( 3, 'Charcoal_SQLCriteria', $criteria );

		$table_name = $model->getTableName();

		$sql = "DELETE FROM " . us($table_name);

		if ( !$criteria ){
			return $sql;
		}

		$where_clause = $criteria->getWhere();
		$limit        = $criteria->getLimit();
		$offset       = $criteria->getOffset();

		if ( !empty($where_clause) ){
			$sql .= ' WHERE ' . $where_clause;
		}
		if ( !empty($limit) ){
			$sql .= ' LIMIT ' . $limit;
		}
		if ( !empty($offset) ){
			$sql .= ' OFFSET ' . $offset;
		}
	
		return $sql;
	}

}
