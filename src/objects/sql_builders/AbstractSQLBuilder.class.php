<?php
/**
* base class for SQL builder
*
* PHP version 5
*
* @package    sql_builders
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
abstract class Charcoal_AbstractSQLBuilder extends Charcoal_CharcoalObject implements Charcoal_ISQLBuilder
{
	private $_type_mapping;

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$this->_type_mapping = $config->getArray( s('type_mappings') );
	}

	/*
	 * SQLビルダ名を取得
	 */
	public function getSQLBuilderName()
	{
		return "default SQL builder";
	}

	/*
	 * タイプマッピング
	 */
	public function mapType( $type_name )
	{
		return isset($this->_type_mapping[$type_name]) ? $this->_type_mapping[$type_name] : $type_name;
	}

	/*
	 *	SQL作成(SELECT)
	 */
	public  function buildSelectSQL( 
					Charcoal_ITableModel $model, 
					Charcoal_Integer $options, 
					Charcoal_SQLCriteria $criteria, 
					Charcoal_String $alias, 
					Charcoal_Vector $joins, 
					Charcoal_Vector $fields
				)
	{
		$options = ui($options);

		$table = $model->getTableName();

		$out_fields = '';
		foreach( $fields as $field ){
			$field = trim($field);
			if ( strlen($out_fields) > 0 ){
				$out_fields .= ',';
			}
			$out_fields .= $field;
		}

		if ( ($options && Charcoal_EnumQueryOption::DISTINCT) === Charcoal_EnumQueryOption::DISTINCT ){
			$sql = "SELECT DISTINCT {$out_fields} FROM " . us($table);
		}
		else{
			$sql = "SELECT {$out_fields} FROM " . us($table);
		}
	
		if ( !$alias->isEmpty() ){
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

		if ( $where_clause && !$where_clause->isEmpty() ){
			$sql .= ' WHERE ' . $where_clause;
		}
		if ( $order_by && !$order_by->isEmpty() ){
			$sql .= ' ORDER BY ' . $order_by;
		}
		if ( $limit != NULL ){
			$sql .= ' LIMIT ' . $limit;
		}
		if ( $offset != NULL ){
			$sql .= ' OFFSET ' . $offset;
		}
		if ( $group_by && !$group_by->isEmpty() ){
			$sql .= ' GROUP BY ' . $group_by;
		}

		if ( ($options && Charcoal_EnumQueryOption::FOR_UPDATE) === Charcoal_EnumQueryOption::FOR_UPDATE ){
			$sql .= " FOR UPDATE";
		}
	
		return s($sql);
	}

	/*
	 *	SQL作成(UPDATE)
	 */
	public  function buildUpdateSQL( Charcoal_ITableModel $model, Charcoal_DTO $dto, Charcoal_SQLCriteria $criteria, Charcoal_Properties $override = NULL  )
	{
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

	/*
	 *	SQL作成(INSERT)
	 */
	public  function buildInsertSQL( Charcoal_ITableModel $model, Charcoal_DTO $dto, Charcoal_Properties $override = NULL  )
	{
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

	/*
	 *	SQL作成(MIN/MAX/SUM/COUNT/AVG)
	 */
	public  function buildAggregateSQL( 
					Charcoal_Integer $aggregate_func, 
					Charcoal_ITableModel $model, 
					Charcoal_SQLCriteria $criteria, 
					Charcoal_String $alias, 
					Charcoal_Vector $joins, 
					Charcoal_String $fields
				)
	{
		$aggregate_func = ui($aggregate_func);

		$table_name = $model->getTableName();

		$aggregate_func_map = array(
				Charcoal_EnumSQLAggregateFunc::FUNC_MIN => 'MIN',
				Charcoal_EnumSQLAggregateFunc::FUNC_MAX => 'MAX',
				Charcoal_EnumSQLAggregateFunc::FUNC_SUM => 'SUM',
				Charcoal_EnumSQLAggregateFunc::FUNC_COUNT => 'COUNT',
				Charcoal_EnumSQLAggregateFunc::FUNC_AVG => 'AVG',
			);

		$func = $aggregate_func_map[$aggregate_func];

		$sql = "SELECT $func($fields) FROM " . us($table_name);

		if ( !$alias->isEmpty() ){
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

		if ( $where_clause && !$where_clause->isEmpty() ){
			$sql .= ' WHERE ' . $where_clause;
		}
		if ( $limit != NULL ){
			$sql .= ' LIMIT ' . $limit;
		}
		if ( $offset != NULL ){
			$sql .= ' OFFSET ' . $offset;
		}
		if ( $group_by && !$group_by->isEmpty() ){
			$sql .= ' GROUP BY ' . $group_by;
		}

		return s($sql);
	}

	/*
	 *	SQL作成(DELETE)
	 */
	public  function buildDeleteSQL( Charcoal_ITableModel $model, Charcoal_SQLCriteria $criteria )
	{
		$table_name = $model->getTableName();

		$sql = "DELETE FROM " . us($table_name);

		if ( !$criteria ){
			return $sql;
		}

		$where_clause = $criteria->getWhere();
		$limit        = $criteria->getLimit();
		$offset       = $criteria->getOffset();

		if ( $where_clause && !$where_clause->isEmpty() ){
			$sql .= ' WHERE ' . $where_clause;
		}
		if ( $limit != NULL ){
			$sql .= ' LIMIT ' . $limit;
		}
		if ( $offset != NULL ){
			$sql .= ' OFFSET ' . $offset;
		}
	
		return $sql;
	}

	/*
	 *	ページ情報からLIMIT句で指定する値を生成
	 */
	public  function getLimit( Charcoal_DBPageInfo $page_info )
	{
		$page_size = $page_info->getPageSize();

		return i($page_size);
	}

	/*
	 *	ページ情報からOFFSET句で指定する値を生成
	 */
	public  function getOffset( Charcoal_DBPageInfo $page_info )
	{
		$page = $page_info->getPage();
		$page_size = $page_info->getPageSize();

		$offset = ($page - 1) * $page_size;

		return i($offset);
	}

}

