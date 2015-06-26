<?php

/**
* implementation class of SmartGateway
*
* PHP version 5
*
* @package    component.charcoal.db
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
require_once( 'EnumQueryOption.class.php' );

class Charcoal_SmartGatewayImpl
{
	private $sandbox;
	private $data_source;
	private $sql_builder;

	private $model_cache;

	/**
	 * Constructor
	 *
	 * @param Charcoal_Sandbox $sandbox           sandbox object
	 * @param Charcoal_IDataSource $data_source   data source object
	 * @param Charcoal_ISQLBuilder $sql_builder   SQL builder object
	 */
	public function __construct( $sandbox, $data_source, $sql_builder )
	{
		$this->sandbox     = $sandbox;
		$this->data_source = $data_source;
		$this->sql_builder = $sql_builder;
	}

	/**
	 *	get data source
	 *	
	 *	@return Charcoal_IDataSource        currently selected data source
	 */
	public function getDataSource()
	{
		return $this->data_source;
	}

	/**
	 *	select data source
	 *	
	 *	@param Charcoal_IDataSource $data_source       data source to select
	 */
	public function setDataSource( $data_source )
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_IDataSource', $data_source );

		$this->data_source = $data_source;
	}

	/**
	 *	get last exectuted SQL
	 *	
	 *	@return Charcoal_ExecutedSQL       executed SQL
	 */
	public function popExecutedSQL()
	{
		return $this->data_source->popExecutedSQL();
	}

	/**
	 *	get all exectuted SQLs
	 *	
	 *	@return array       executed SQLs
	 */
	public function getAllExecutedSQL()
	{
		return $this->data_source->getAllExecutedSQL();
	}

	/**
	 *	Close connection and destory components
	 */
	public function terminate()
	{
		if ( $this->data_source ){
			$this->data_source->disconnect();
		}
	}

	/**
	 *	Get last insert id
	 */
	public function getLastInsertId()
	{
		return $this->data_source->getLastInsertId();
	}

	/**
	 *  returns count of rows which are affected by previous SQL(DELETE/INSERT/UPDATE)
	 *  
	 *  @return int         count of rows
	 */
	public function numRows()
	{
		return $this->data_source->numRows();
	}

	/**
	 *	get table model
	 *	
	 *	@param string $model_name       table model name
	 */
	private function getModel( $model_name )
	{
//		Charcoal_ParamTrait::validateString( 1, $model_name );

		$model_name = us($model_name);

		if ( isset($this->model_cache[$model_name]) ){
			return $this->model_cache[$model_name];
		}

		// create new instance
		$model = $this->sandbox->createObject( $model_name, 'table_model' );

		$model->setModelID( $model_name );

		// set in cache
		$this->model_cache[$model_name] = $model;

		return $model;
	}

	/**
	 *   create recordset factory
	 *
	 * @param integer $fetch_mode    fetch mode(defined at Charcoal_IRecordset::FETCHMODE_XXX)
	 * @param array $options         fetch mode options
	 */
	public function createRecordsetFactory( $fetch_mode = NULL, $options = NULL )
	{
		return $this->data_source->createRecordsetFactory( $fetch_mode, $options );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::autoCommit()
	 * 
	 * @param bool $on          If TRUE, transaction will be automatically comitted.
	 */
	public function autoCommit( $on )
	{
		$this->data_source->autoCommit( $on );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::beginTrans()
	 */
	public function beginTrans()
	{
		$this->data_source->beginTrans();
	}

	/**
	 *	execute transaction command: COMMIT
	 */
	public function commitTrans()
	{
		$this->data_source->commitTrans();
	}

	/**
	 *	execute transaction command: ROLLBACK
	 */
	public function rollbackTrans()
	{
		$this->data_source->rollbackTrans();
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::execute()
	 *	
	 * @param string|Charcoal_String $sql              SQL statement(placeholders can be included)
	 * @param array|Charcoal_HashMap $params           Parameter values for prepared statement
	 * @param array|Charcoal_HashMap $driver_options   Driver options
	 */
	public function execute( $sql, $params = NULL, $driver_options = NULL )
	{
		Charcoal_ParamTrait::validateString( 1, $sql );
		Charcoal_ParamTrait::validateHashMap( 2, $params, TRUE );
		Charcoal_ParamTrait::validateHashMap( 3, $driver_options, TRUE );

		$this->data_source->prepareExecute( $sql, $params, $driver_options );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::query()
	 *	
	 * @param string|Charcoal_String $sql              SQL statement(placeholders can be included)
	 * @param array|Charcoal_HashMap $params           Parameter values for prepared statement
	 *	@param Charcoal_IRecordsetFactory $recordsetFactory
	 * @param array|Charcoal_HashMap $driver_options   Driver options
	 */
	public function query( $sql, $params = NULL, $recordsetFactory = NULL, $driver_options = NULL )
	{
		Charcoal_ParamTrait::validateString( 1, $sql );
		Charcoal_ParamTrait::validateHashMap( 2, $params, TRUE );
		Charcoal_ParamTrait::validateIsA( 3, 'Charcoal_IRecordsetFactory', $recordsetFactory, TRUE );
		Charcoal_ParamTrait::validateHashMap( 4, $driver_options, TRUE );

		$result = $this->data_source->prepareExecute( $sql, $params, $driver_options );

		if ( $recordsetFactory )
		{
			return $recordsetFactory->createRecordset( $result, Charcoal_IRecordset::FETCHMODE_BOTH );
		}
		else{
			$a = array();
			while( $row = $this->data_source->fetchAssoc( $result ) ){
				$a[] = $row;
			}

			$this->data_source->free( $result );

			return $a;
		}
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::queryValue()
	 *	
	 * @param string|Charcoal_String $sql              SQL statement(placeholders can be included)
	 * @param array|Charcoal_HashMap $params           Parameter values for prepared statement
	 * @param array|Charcoal_HashMap $driver_options   Driver options
	 */
	public function queryValue( $sql, $params = NULL, $driver_options = NULL )
	{
		Charcoal_ParamTrait::validateString( 1, $sql );
		Charcoal_ParamTrait::validateHashMap( 2, $params, TRUE );
		Charcoal_ParamTrait::validateHashMap( 3, $driver_options, TRUE );

		$result = $this->data_source->prepareExecute( $sql, $params, $driver_options );

		while( $row = $this->data_source->fetchAssoc( $result ) ){
			$value = array_shift($row);
			log_debug( "debug,smart_gateway,sql", "queryValue:$value" );
			return $value;
		}

		$this->data_source->free( $result );

		log_warning( "debug,smart_gateway,sql", "smart_gateway", "queryValue: no record" );

		return NULL;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::find()
	 *	
	 * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 * @param int $options
	 * @param Charcoal_SQLCriteria $criteria
	 * @param Charcoal_String|string $fields
	 * @param Charcoal_IRecordsetFactory $recordsetFactory
	 * @param array|Charcoal_HashMap $driver_options   Driver options
	 */
	public function find( $query_target, $options, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateInteger( 2, $options );
		Charcoal_ParamTrait::validateIsA( 3, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::validateString( 4, $fields, TRUE );
		Charcoal_ParamTrait::validateIsA( 5, 'Charcoal_IRecordsetFactory', $recordsetFactory, TRUE );
		Charcoal_ParamTrait::validateHashMap( 6, $driver_options, TRUE );

		$current_model_name  = $query_target->getModelName();
		$current_model_alias = $query_target->getAlias();
		$current_model_joins = $query_target->getJoins();
	
		// get current model
		$current_model = $this->getModel( $current_model_name );

		$current_table_name  = $current_model->getTableName();

		// make output fields
		if ( $fields !== NULL ){
			$fields = explode( ',', $fields );
		}
		else{
			// pickup fields from model
			$fields = $current_model->getFieldList();

			// add alias or table name as prefix
			$out_fields = NULL;
			foreach( $fields as $field ){
				$out_field = $field;
				if ( strlen($current_model_alias) > 0 ){
					$out_field = $current_model_alias . '.' . $out_field;
				}
				elseif ( $current_model_joins && count($current_model_joins) > 0 ){
					$out_field = $current_table_name . '.' . $out_field;
				}
				$out_fields[] = $out_field;
			}

			// add join fields
			if ( $current_model_joins ){
				foreach( $current_model_joins as $join ){
					$join_model_name = $join->getModelName();
					$join_alias = $join->getAlias();

					$join_model = $this->getModel( $join_model_name );
					$join_fields = $join_model->getFieldList();
					foreach( $join_fields as $field ){
						$out_field = $field;
						if ( strlen($join_alias) > 0 ){
							$out_field = $join_alias . '.' . $out_field;
						}
						else{
							$out_field = $join_model->getTableName() . '.' . $out_field;
						}
						$out_fields[] = $out_field;
					}
				}
			}

			// make vector fields
			$fields = new Charcoal_Vector( $out_fields );
		}

		// SQLの作成
		$sql = $this->sql_builder->buildSelectSQL( $current_model, $current_model_alias, $options, $criteria, $current_model_joins, $fields );
//			log_debug( "debug,smart_gateway,sql", "SQL: $sql" );

		// パラメータ
		$params = $criteria->getParams();
//			log_debug( "debug,smart_gateway,sql", "params: $params" );

		// 実行
		$result = $this->data_source->prepareExecute( $sql, $params, $driver_options );

//			log_debug( "debug,smart_gateway,sql", "executed SQL: $sql" );

		// 実行結果件数取得
		$num_rows = $this->data_source->numRows( $result );

//			log_debug( "debug,smart_gateway,sql", "num_rows: $num_rows" );

		if ( $recordsetFactory )
		{
			return $recordsetFactory->createRecordset( $result, Charcoal_IRecordset::FETCHMODE_BOTH );
		}
		else{
			$rows = array();
			while( $row = $this->data_source->fetchAssoc( $result ) )
			{
				$rows[] = new Charcoal_HashMap( $row );
			}

			$this->data_source->free( $result );

			return $rows;
		}
	}


	/**
	 *	real implementation of Charcoal_SmartGateway::findFirst()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 */
	public function findFirst( $query_target, $criteria, $fields = NULL ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::validateString( 3, $fields, TRUE );

		$criteria->setLimit( 1 );

		$result = $this->findAll( $query_target, $criteria, $fields );

		return $result ? array_shift($result) : NULL;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::findFirstForUpdate()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 */
	public function findFirstForUpdate( $query_target, $criteria, $fields = NULL ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::validateString( 3, $fields, TRUE );

		$criteria->setLimit( 1 );

		$result = $this->findAllForUpdate( $query_target, $criteria, $fields );

		return $result ? array_shift($result) : NULL;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::findAll()
	 *	
	 * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 * @param Charcoal_SQLCriteria $criteria
	 * @param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 * @param Charcoal_IRecordsetFactory $recordsetFactory
	 * @param array|Charcoal_HashMap $driver_options   Driver options
	 */
	public function findAll( $query_target, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL ) 
	{
		return $this->find( $query_target, 0, $criteria, $fields, $recordsetFactory, $driver_options );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::findAllForUpdate()
	 *	
	 * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 * @param Charcoal_SQLCriteria $criteria
	 * @param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 * @param Charcoal_IRecordsetFactory $recordsetFactory
	 * @param array|Charcoal_HashMap $driver_options   Driver options
	 */
	public function findAllForUpdate( $query_target, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL ) 
	{
		return $this->find( $query_target, Charcoal_EnumQueryOption::FOR_UPDATE, $criteria, $fields, $recordsetFactory, $driver_options );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::findAllDistinct()
	 *	
	 * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 * @param Charcoal_SQLCriteria $criteria
	 * @param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 * @param Charcoal_IRecordsetFactory $recordsetFactory
	 * @param array|Charcoal_HashMap $driver_options   Driver options
	 */
	public function findAllDistinct( $query_target, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL ) 
	{
		return $this->find( $query_target, Charcoal_EnumQueryOption::DISTINCT, $criteria, $fields, $recordsetFactory, $driver_options );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::findAllBy()
	 *	
	 * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 * @param Charcoal_SQLCriteria $criteria
	 * @param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 * @param Charcoal_IRecordsetFactory $recordsetFactory
	 * @param array|Charcoal_HashMap $driver_options   Driver options
	 */
	public function findAllBy( $query_target, $field, $value, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL )
	{
		$field = us( $field );

		$criteria = new Charcoal_SQLCriteria();

		$criteria->setWhere( $field . ' = ?' );
		$criteria->setParams( array( $value ) );

		return $this->findAll( $query_target, $criteria, $fields, $recordsetFactory, $driver_options );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::findById()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param int|bool|float|string $data_id        primary key value of the entity
	 */
	public function findById( $query_target, $id ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateScalar( 2, $id );

		$model = $this->getModel( $query_target->getModelName() );

		$pk = $model->getPrimaryKey();

		$where_clause = $pk . ' = ?';
		$params = array( ui($id) );

		$criteria = new Charcoal_SQLCriteria( $where_clause, $params );

		$query_target = new Charcoal_QueryTarget( $model->getTableName() );

		$result = $this->findAll( $query_target, $criteria );

		return $result ? array_shift( $result ) : NULL;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::deleteById()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param int|bool|float|string $data_id        primary key value of the entity
	 */
	public function deleteById( $query_target, $data_id ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateScalar( 2, $data_id );

		$model = $this->getModel( $query_target->getModelName() );
		$alias = $query_target->getAlias();

		$data_id = us( $data_id );

		$pk = us($model->getPrimaryKey());

		$where = us($pk) . ' = ?';
		$params = array( ui($data_id) );

		$criteria = new Charcoal_SQLCriteria( $where, $params );

		$sql = $this->sql_builder->buildDeleteSQL( $model, $alias, $criteria );

///			log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//			log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

		$this->execute( $sql, $params );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::deleteBy()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param string $field                         field name to query
	 *	@param Charcoal_Scalar $value                field value to query
	 */
	public function deleteBy( $query_target, $field, $value )
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateString( 2, $field );
		Charcoal_ParamTrait::validateScalar( 3, $value );

		$value = ($value instanceof Charcoal_Scalar) ? $value->unbox() : $value;

		$where = us($field) . ' = ?';
		$params = array( $value );

		$criteria = new Charcoal_SQLCriteria( $where, $params );

		$this->bulkDelete( $query_target, $criteria );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::bulkDelete()
	 *	
	 *	@param Charcoal_QueryTarget $query_target     description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria         criteria object
	 */
	public function bulkDelete( $query_target, $criteria ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_SQLCriteria', $criteria );

		$model = $this->getModel( $query_target->getModelName() );
		$alias = $query_target->getAlias();

		$sql = $this->sql_builder->buildDeleteSQL( $model, $alias, $criteria );

		$params = $criteria ? $criteria->getParams() : NULL;

//			log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//			log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

		$this->execute( $sql, $params );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::execAggregateQuery()
	 *	
	 *	@param int $aggregate_func                   identify aggregate function tpype
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria        criteria object
	 *	@param Charcoal_String|string $fields        comma-separated field list: like 'A,B,C...'
	 *	@param Charcoal_String|string $comment       comment text
	 */
	private  function execAggregateQuery( $aggregate_func, $query_target, $criteria, $fields = NULL, $comment = NULL ) 
	{
		Charcoal_ParamTrait::validateInteger( 1, $aggregate_func );
		Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateIsA( 3, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::validateString( 4, $fields, TRUE );
		Charcoal_ParamTrait::validateString( 5, $comment, TRUE );

		// default count fields
		if ( $fields === NULL ){
			$fields = '*';
		}
		$fields = explode( ',', $fields );

		$current_model_name  = $query_target->getModelName();
		$current_model_alias = $query_target->getAlias();
		$current_model_joins = $query_target->getJoins();

		// get current model
		$model = $this->getModel( $current_model_name );

		$sql = $this->sql_builder->buildAggregateSQL( 
										$model, 
										$current_model_alias, 
										$aggregate_func, 
										$criteria, 
										$current_model_joins, 
										$fields, 
										$comment );

		$params = $criteria->getParams();

//			log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//			log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

		// SQL実行
		$result = $this->data_source->prepareExecute( $sql, $params );

		// フェッチ
		$rows = $this->data_source->fetchArray( $result );

		// result
		$result = $rows[0] ? intval($rows[0]) : 0;

		return $result;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::count()
	 *	
	 *	@param Charcoal_QueryTarget $query_target     description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria        criteria object
	 *	@param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 *	@param Charcoal_String|string $comment       comment text
	 */
	public function count( $query_target, $criteria, $fields = NULL, $comment = NULL ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::validateString( 3, $fields, TRUE );
		Charcoal_ParamTrait::validateString( 4, $comment, TRUE );

		if ( $fields === NULL ){
			$fields = '*';
		}

		$result = $this->execAggregateQuery( Charcoal_EnumSQLAggregateFunc::FUNC_COUNT, $query_target, $criteria, $fields, $comment );

		log_debug( "debug,sql,smart_gateway", "smart_gateway", "COUNT result: $result" );

		return $result;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::max()
	 *	
	 *	@param Charcoal_QueryTarget $query_target     description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria        criteria object
	 *	@param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 *	@param Charcoal_String|string $comment       comment text
	 */
	public function max( $query_target, $criteria, $fields = NULL, $comment = NULL ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::validateString( 3, $fields, TRUE );
		Charcoal_ParamTrait::validateString( 4, $comment, TRUE );

		$result = $this->execAggregateQuery( Charcoal_EnumSQLAggregateFunc::FUNC_MAX, $query_target, $criteria, $fields, $comment );

		log_debug( "debug,sql,smart_gateway", "smart_gateway", "MAX result: $result" );

		return $result;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::min()
	 *	
	 *	@param Charcoal_QueryTarget $query_target     description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria        criteria object
	 *	@param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 *	@param Charcoal_String|string $comment       comment text
	 */
	public function min( $query_target, $criteria, $fields = NULL, $comment = NULL ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::validateString( 3, $fields );
		Charcoal_ParamTrait::validateString( 4, $comment, TRUE );

		$result = $this->execAggregateQuery( Charcoal_EnumSQLAggregateFunc::FUNC_MIN, $query_target, $criteria, $fields, $comment );

		log_debug( "debug,sql,smart_gateway", "smart_gateway", "MIN result: $result" );

		return $result;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::sum()
	 *	
	 *	@param Charcoal_QueryTarget $query_target     description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria         criteria object
	 *	@param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 *	@param Charcoal_String|string $comment        comment text
	 */
	public function sum( $query_target, $criteria, $fields = NULL, $comment = NULL ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::validateString( 3, $fields );
		Charcoal_ParamTrait::validateString( 4, $comment, TRUE );

		$result = $this->execAggregateQuery( Charcoal_EnumSQLAggregateFunc::FUNC_SUM, $query_target, $criteria, $fields, $comment );

		log_debug( "debug,sql,smart_gateway", "smart_gateway", "SUM result: $result" );

		return $result;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::avg()
	 *	
	 *	@param Charcoal_QueryTarget $query_target     description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria         criteria object
	 *	@param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 *	@param Charcoal_String|string $comment        comment text
	 */
	public function avg( $query_target, $criteria, $fields = NULL, $comment = NULL ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::validateString( 3, $fields );
		Charcoal_ParamTrait::validateString( 4, $comment, TRUE );

		$result = $this->execAggregateQuery( Charcoal_EnumSQLAggregateFunc::FUNC_AVG, $query_target, $criteria, $fields, $comment );

		log_debug( "debug,sql,smart_gateway", "smart_gateway", "AVG result: $result" );

		return $result;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::createTable()
	 *	
	 *	@param string $model_name
	 *	@param boolean|Charcoal_Boolean $if_not_exists        If TRUE, output SQL includes "IF NOT EXISTS" wuth "CREATE TABLE"
	 */
	public function createTable( $model_name, $if_not_exists = false ) 
	{
		Charcoal_ParamTrait::validateString( 1, $model_name );
		Charcoal_ParamTrait::validateBoolean( 2, $if_not_exists );

		$model = $this->getModel( $model_name );

		$sql = $this->sql_builder->buildCreateTableSQL( $model, $if_not_exists );

		$this->data_source->execute( $sql );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::dropTable()
	 *	
	 *	@param string $model_name
	 *	@param boolean|Charcoal_Boolean $if_exists        If TRUE, output SQL includes "IF EXISTS" wuth "DROP TABLE"
	 */
	public function dropTable( $model_name, $if_exists = false ) 
	{
		Charcoal_ParamTrait::validateString( 1, $model_name );
		Charcoal_ParamTrait::validateBoolean( 2, $if_exists );

		$model = $this->getModel( $model_name );

		$sql = $this->sql_builder->buildDropTableSQL( $model, $if_exists );

		$this->data_source->execute( $sql );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::truncateTable()
	 *	
	 *	@param string $model_name
	 */
	public function truncateTable( $model_name ) 
	{
		Charcoal_ParamTrait::validateString( 1, $model_name );

		$model = $this->getModel( $model_name );

		$sql = $this->sql_builder->buildTruncateTableSQL( $model );

		$this->data_source->execute( $sql );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::save()
	 *	
	 *	@param Charcoal_QueryTarget $query_target      description about target model, alias, or joins
	 *	@param array $data                             associative array/HashMap/DTO object to insert
	 */
	public function save( $query_target, $data )
	{
		try{
			Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
			Charcoal_ParamTrait::validateHashMapOrDTO( 2, $data );

			$model = $this->getModel( $query_target->getModelName() );
			$alias = $query_target->getAlias();

			// primary key
			$pk      = $model->getPrimaryKey();

			// validate primary key value
			$valid = $model->validatePrimaryKeyValue( $data );

			if ( $valid ){
				// find entity
				$obj = self::findById( $query_target, $data->$pk );
				// if not found, dto is regarded as a new entity
				$is_new = empty($obj);
			}
			else{
				// if promary key value is invalid, dto id rebgarded as a new entity
				$is_new = true;
			}

			// build SQL
			if ( !$is_new ){
				// UPDATE
				$data_id = $data[$pk];

				$where = "$pk = ?";
				$params = array( ui($data_id) );
				$criteria = new Charcoal_SQLCriteria( $where, $params );
				list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $alias, $data, $criteria );
			}
			else{
				// INSERT
				list( $sql, $params ) = $this->sql_builder->buildInsertSQL( $model, $alias, $data );

				$is_new = TRUE;
			}

			$this->data_source->prepareExecute( $sql, $params );

			if ( $is_new ){
				$sql = $this->sql_builder->buildLastIdSQL();

				$result = $this->data_source->prepareExecute( $sql );

				$row = $this->data_source->fetchArray( $result );

				$new_id = $row[0];
			}
			else{
				$new_id = $data[$pk];
			}

			log_debug( "debug,smart_gateway,sql", "new_id:$new_id" );

			return $new_id;
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::insert()
	 *	
	 *	@param string|Charcoal_QueryTarget $query_target      description about target model, alias, or joins
	 *	@param array $data                             associative array/HashMap/DTO object to insert
	 */
	public function insert( $query_target, $data )
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateHashMapOrDTO( 2, $data );

		$model = $this->getModel( $query_target->getModelName() );
		$alias = $query_target->getAlias();

		list( $sql, $params ) = $this->sql_builder->buildInsertSQL( $model, $alias, $data );

		$this->data_source->prepareExecute( $sql, $params );

		$sql = $this->sql_builder->buildLastIdSQL();
		
		$result = $this->data_source->prepareExecute( $sql );

		$row = $this->data_source->fetchArray( $result );

		$new_id = $row[0];
		log_debug( "debug,smart_gateway,sql", "new_id:$new_id" );

		return $new_id;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::bulkInsert()
	 *	
	 *	@param string|Charcoal_QueryTarget $query_target      description about target model, alias, or joins
	 *	@param array|CharcoalVector $data_set                 array of array or DTO value to insert
	 */
	public function bulkInsert( $query_target, $data_set )
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateVector( 2, $data_set );

		$model = $this->getModel( $query_target->getModelName() );
		$alias = $query_target->getAlias();

		list( $sql, $params ) = $this->sql_builder->buildBulkInsertSQL( $model, $alias, $data_set );

		$this->data_source->prepareExecute( $sql, $params );

		$sql = $this->sql_builder->buildLastIdSQL();
		
		$result = $this->data_source->prepareExecute( $sql );

		$row = $this->data_source->fetchArray( $result );

		$new_id = $row[0];
		log_debug( "debug,smart_gateway,sql", "new_id:$new_id" );

		return $new_id;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::updateFields()
	 *	
	 *	@param Charcoal_QueryTarget $query_target description about target model, alias, or joins
	 *	@param int $data_id                       identify database entity
	 *	@param array $data                        associative array or HashMap object to update
	 */
	public function updateFields( $query_target, $data_id, $data ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateInteger( 2, $data_id );
		Charcoal_ParamTrait::validateHashMap( 3, $data );

		$model = $this->getModel( $query_target->getModelName() );
		$alias = $query_target->getAlias();

		$dto = $model->createDTO();

		$data = up($data);
		foreach( $data as $field => $value ){
			$dto->$field = $value;
		}

//		log_debug( "debug,smart_gateway,sql", "dto:" . print_r($dto,true) );

		$pk = $model->getPrimaryKey();
		$where = "$pk = ?";
		$params = array( ui($data_id) );
		$criteria = new Charcoal_SQLCriteria( $where, $params );

		list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $alias, $dto, $criteria );

//		log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//		log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

		// SQL実行
		$this->data_source->prepareExecute( $sql, $params );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::updateFieldNow()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param int $data_id                          identify database entity
	 *	@param string $field                         field name to update
	 */
	public function updateFieldNow( $query_target, $data_id, $field ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateInteger( 2, $data_id );
		Charcoal_ParamTrait::validateString( 3, $field );

		$model = $this->getModel( $query_target->getModelName() );
		$alias = $query_target->getAlias();

		if ( !$model->fieldExists($field) ){
			_throw( new Charcoal_InvalidArgumentException("field=[$field]") );
		}

		$field = us($field);

		$override[$field]['update'] = new Charcoal_AnnotationValue( 'update', 'function', array('now') );

		$pk = $model->getPrimaryKey();
		$where = "$pk = ?";
		$params = array( ui($data_id) );
		$criteria = new Charcoal_SQLCriteria( $where, $params );

		list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $alias, $model->createDTO(), $criteria, $override );

//		log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//		log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

		$this->data_source->prepareExecute( $sql, $params );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::incrementField()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param int $data_id                          identify database entity
	 *	@param string $field                         field name to increment
	 *	@param int $increment_by                     amount of increment
	 */
	public function incrementField( $query_target, $data_id, $field, $increment_by = 1 ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateInteger( 2, $data_id );
		Charcoal_ParamTrait::validateString( 3, $field );

		$model = $this->getModel( $query_target->getModelName() );
		$alias = $query_target->getAlias();

		if ( !$model->fieldExists($field) ){
			_throw( new Charcoal_InvalidArgumentException("field=[$field]") );
		}

		$field = us($field);

		$override[$field]['update'] = new Charcoal_AnnotationValue( 'update', 'function', array('increment', $increment_by) );

		$pk = $model->getPrimaryKey();
		$where = "$pk = ?";
		$params = array( ui($data_id) );
		$criteria = new Charcoal_SQLCriteria( $where, $params );

		list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $alias, $model->createDTO(), $criteria, $override );

//		log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//		log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

		$this->data_source->prepareExecute( $sql, $params );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::decrementField()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param int $data_id                          identify database entity
	 *	@param string $field                         field name to decrement
	 *	@param int $decrement_by                     amount of decrement
	 */
	public function decrementField( $query_target, $data_id, $field, $decrement_by = 1 ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateInteger( 2, $data_id );
		Charcoal_ParamTrait::validateString( 3, $field );

		$model = $this->getModel( $query_target->getModelName() );
		$alias = $query_target->getAlias();

		if ( !$model->fieldExists($field) ){
			_throw( new Charcoal_InvalidArgumentException("field=[$field]") );
		}

		$field = us($field);

		$override[$field]['update'] = new Charcoal_AnnotationValue( 'update', 'function', array('decrement', $decrement_by) );

		$pk = $model->getPrimaryKey();
		$where = "$pk = ?";
		$params = array( ui($data_id) );
		$criteria = new Charcoal_SQLCriteria( $where, $params );

		list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $alias, $model->createDTO(), $criteria, $override );

//		log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//		log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

		$this->data_source->prepareExecute( $sql, $params );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::updateFieldNull()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param int $data_id                          identify database entity
	 *	@param string $field                         field name to set null
	 */
	public function updateFieldNull( $query_target, $data_id, $field ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateInteger( 2, $data_id );
		Charcoal_ParamTrait::validateString( 3, $field );

		$model = $this->getModel( $query_target->getModelName() );
		$alias = $query_target->getAlias();

		if ( !$model->fieldExists($field) ){
			_throw( new Charcoal_InvalidArgumentException("field=[$field]") );
		}

		$field = us($field);

		$override[$field]['update'] = new Charcoal_AnnotationValue( 'update', 'function', array('set_null') );

		$pk = $model->getPrimaryKey();
		$where = "$pk = ?";
		$params = array( ui($data_id) );
		$criteria = new Charcoal_SQLCriteria( $where, $params );

		list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $alias, $model->createDTO(), $criteria, $override );

//		log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//		log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

		$this->data_source->prepareExecute( $sql, $params );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::updateField()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param int $data_id                          identify database entity
	 *	@param string $field                         field name to update
	 *	@param Charcoal_Scalar $value                scalar primitive data to update
	 */
	public function updateField( $query_target, $data_id, $field, $value ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateInteger( 2, $data_id );
		Charcoal_ParamTrait::validateString( 3, $field );
		Charcoal_ParamTrait::validateScalar( 4, $value );

		$model = $this->getModel( $query_target->getModelName() );
		$alias = $query_target->getAlias();

		$dto = $model->createDTO();

		$dto->$field = ($value instanceof Charcoal_Scalar) ? $value->unbox() : $value;

//		log_debug( "debug,smart_gateway,sql", "dto:" . print_r($dto,true) );

//		log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//		log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

		$pk = $model->getPrimaryKey();
		$where = "$pk = ?";
		$params = array( ui($data_id) );
		$criteria = new Charcoal_SQLCriteria( $where, $params );

		list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $alias, $dto, $criteria );

//		log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//		log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

		$this->data_source->prepareExecute( $sql, $params );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::updateAll()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria        criteria object
	 *	@param array $data                           associative array or HashMap object to update
	 */
	public function updateAll( $query_target, $criteria, $data ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::validateHashMap( 3, $data );

		$model = $this->getModel( $query_target->getModelName() );
		$alias = $query_target->getAlias();

		$dto = $model->createDTO( um($data) );

		list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $dto, $criteria );

//		log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//		log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

		// SQL実行
		$this->data_source->prepareExecute( $sql, $params );
	}

}

