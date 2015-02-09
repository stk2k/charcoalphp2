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
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_IDataSource', $data_source );

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
	 *	get table model
	 *	
	 *	@param string $model_name       table model name
	 */
	private function getModel( $model_name )
	{
//		Charcoal_ParamTrait::checkString( 1, $model_name );

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
	 *	@param string $sql
	 *	@param array $params
	 */
	public function execute( $sql, $params = NULL )
	{
		Charcoal_ParamTrait::checkString( 1, $sql );
		Charcoal_ParamTrait::checkVector( 2, $params, TRUE );

		$this->data_source->prepareExecute( $sql, $params );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::query()
	 *	
	 *	@param string $sql
	 *	@param array $params
	 */
	public function query( $sql, $params = NULL )
	{
		Charcoal_ParamTrait::checkString( 1, $sql );
		Charcoal_ParamTrait::checkVector( 2, $params, TRUE );

		$result = $this->data_source->prepareExecute( $sql, $params );

		$a = array();
		while( $row = $this->data_source->fetchAssoc( $result ) ){
			$a[] = $row;
		}

		return $a;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::queryValue()
	 *	
	 *	@param string $sql
	 *	@param array $params
	 */
	public function queryValue( $sql, $params = NULL )
	{
		Charcoal_ParamTrait::checkString( 1, $sql );
		Charcoal_ParamTrait::checkVector( 2, $params, TRUE );

		$result = $this->data_source->prepareExecute( $sql, $params );

		while( $row = $this->data_source->fetchAssoc( $result ) ){
			$value = array_shift($row);
			log_debug( "debug,smart_gateway,sql", "queryValue:$value" );
			return $value;
		}

		log_warning( "debug,smart_gateway,sql", "smart_gateway", "queryValue: no record" );

		return NULL;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::find()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param int $options
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param Charcoal_String|string $fields
	 */
	public function find( $query_target, $options, $criteria, $fields = NULL ) 
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkInteger( 2, $options );
		Charcoal_ParamTrait::checkIsA( 3, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::checkString( 4, $fields, TRUE );

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
		$result = $this->data_source->prepareExecute( $sql, $params );

//			log_debug( "debug,smart_gateway,sql", "executed SQL: $sql" );

		// 実行結果件数取得
		$num_rows = $this->data_source->numRows( $result );

//			log_debug( "debug,smart_gateway,sql", "num_rows: $num_rows" );

		// fetch by record
		$rows = array();
		while( $row = $this->data_source->fetchAssoc( $result ) )
		{
//				log_debug( "debug,smart_gateway,sql", "row: " . print_r($row,true) );

			$rows[] = new Charcoal_HashMap( $row );
		}

		return $rows;
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
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::checkString( 3, $fields, TRUE );

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
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::checkString( 3, $fields, TRUE );

		$criteria->setLimit( 1 );

		$result = $this->findAllForUpdate( $query_target, $criteria, $fields );

		return $result ? array_shift($result) : NULL;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::findAll()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 */
	public function findAll( $query_target, $criteria, $fields = NULL ) 
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::checkString( 3, $fields, TRUE );

		return $this->find( $query_target, 0, $criteria, $fields );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::findAllForUpdate()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 */
	public function findAllForUpdate( $query_target, $criteria, $fields = NULL ) 
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::checkString( 3, $fields, TRUE );

		return $this->find( $query_target, Charcoal_EnumQueryOption::FOR_UPDATE, $criteria, $fields );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::findDistinct()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 */
	public function findDistinct( $query_target, $fields, $criteria, $fields = NULL ) 
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::checkString( 3, $fields, TRUE );

		return $this->find( $query_target, Charcoal_EnumQueryOption::DISTINCT, $criteria, $fields );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::findDistinct()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 */
	public function findAllBy( $query_target, $field, $value, $fields = NULL )
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::checkString( 3, $fields, TRUE );

		$field = us( $field );

		$criteria = new Charcoal_SQLCriteria();

		$criteria->setWhere( $field . ' = ?' );
		$criteria->setParams( array( $value ) );

		return $this->findAll( $query_target, $criteria, $fields );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::findById()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param int $id
	 */
	public function findById( $query_target, $id ) 
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkInteger( 2, $id );

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
	 *	real implementation of Charcoal_SmartGateway::destroyById()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param int $data_id                          identify database entity
	 */
	public function destroyById( $query_target, $data_id ) 
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkInteger( 2, $data_id );

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

		$this->execute( s($sql), v($params) );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::destroyBy()
	 *	
	 *	@param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 *	@param string $field                         field name to query
	 *	@param Charcoal_Scalar $value                field value to query
	 */
	public function destroyBy( $query_target, $field, $value )
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkString( 2, $field );
		Charcoal_ParamTrait::checkScalar( 3, $value );

		$value = ($value instanceof Charcoal_Scalar) ? $value->unbox() : $value;

		$where = us($field) . ' = ?';
		$params = array( $value );

		$criteria = new Charcoal_SQLCriteria( $where, $params );

		$this->destroyAll( $query_target, $criteria );
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::destroyAll()
	 *	
	 *	@param Charcoal_QueryTarget $query_target     description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria         criteria object
	 */
	public function destroyAll( $query_target, $criteria ) 
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );

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
	 *	@param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 */
	private  function execAggregateQuery( $aggregate_func, $query_target, $criteria, $fields = NULL ) 
	{
		Charcoal_ParamTrait::checkInteger( 1, $aggregate_func );
		Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkIsA( 3, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::checkString( 4, $fields, TRUE );

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

		$sql = $this->sql_builder->buildAggregateSQL( $model, $current_model_alias, $aggregate_func, $criteria, $current_model_joins, $fields );

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
	 */
	public function count( $query_target, $criteria, $fields = NULL ) 
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::checkString( 3, $fields, TRUE );

		if ( $fields === NULL ){
			$fields = '*';
		}

		$result = $this->execAggregateQuery( Charcoal_EnumSQLAggregateFunc::FUNC_COUNT, $query_target, $criteria, $fields );

		log_debug( "debug,sql,smart_gateway", "smart_gateway", "COUNT result: $result" );

		return $result;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::max()
	 *	
	 *	@param Charcoal_QueryTarget $query_target     description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria        criteria object
	 *	@param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 */
	public function max( $query_target, $criteria, $fields = NULL  ) 
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::checkString( 3, $fields, TRUE );

		$result = $this->execAggregateQuery( Charcoal_EnumSQLAggregateFunc::FUNC_MAX, $query_target, $criteria, $fields );

		log_debug( "debug,sql,smart_gateway", "smart_gateway", "MAX result: $result" );

		return $result;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::min()
	 *	
	 *	@param Charcoal_QueryTarget $query_target     description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria        criteria object
	 *	@param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 */
	public function min( $query_target, $criteria, $fields ) 
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::checkString( 3, $fields );

		$result = $this->execAggregateQuery( Charcoal_EnumSQLAggregateFunc::FUNC_MIN, $query_target, $criteria, $fields );

		log_debug( "debug,sql,smart_gateway", "smart_gateway", "MIN result: $result" );

		return $result;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::sum()
	 *	
	 *	@param Charcoal_QueryTarget $query_target     description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria         criteria object
	 *	@param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
	 */
	public function sum( $query_target, $criteria, $fields ) 
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::checkString( 3, $fields );

		$result = $this->execAggregateQuery( Charcoal_EnumSQLAggregateFunc::FUNC_SUM, $query_target, $criteria, $fields );

		log_debug( "debug,sql,smart_gateway", "smart_gateway", "SUM result: $result" );

		return $result;
	}

	/**
	 *	real implementation of Charcoal_SmartGateway::avg()
	 *	
	 *	@param Charcoal_QueryTarget $query_target        description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param string $fields
	 */
	public function avg( $query_target, $criteria, $fields ) 
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::checkString( 3, $fields );

		$result = $this->execAggregateQuery( Charcoal_EnumSQLAggregateFunc::FUNC_AVG, $query_target, $criteria, $fields );

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
		Charcoal_ParamTrait::checkString( 1, $model_name );
		Charcoal_ParamTrait::checkBoolean( 2, $if_not_exists );

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
		Charcoal_ParamTrait::checkString( 1, $model_name );
		Charcoal_ParamTrait::checkBoolean( 2, $if_exists );

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
		Charcoal_ParamTrait::checkString( 1, $model_name );

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
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkHashMapOrDTO( 2, $data );

		$model = $this->getModel( $query_target->getModelName() );
		$alias = $query_target->getAlias();

		// primary key
		$pk      = $model->getPrimaryKey();

		// build SQL
		$is_new = FALSE;
		if ( $model->isPrimaryKeyValid($data) ){
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

	/**
	 *	real implementation of Charcoal_SmartGateway::insert()
	 *	
	 *	@param string|Charcoal_QueryTarget $query_target      description about target model, alias, or joins
	 *	@param array $data                             associative array/HashMap/DTO object to insert
	 */
	public function insert( $query_target, $data )
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkHashMapOrDTO( 2, $data );

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
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkVector( 2, $data_set );

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
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkInteger( 2, $data_id );
		Charcoal_ParamTrait::checkHashMap( 3, $data );

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
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkInteger( 2, $data_id );
		Charcoal_ParamTrait::checkString( 3, $field );

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
	 */
	public function incrementField( $query_target, $data_id, $field ) 
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkInteger( 2, $data_id );
		Charcoal_ParamTrait::checkString( 3, $field );

		$model = $this->getModel( $query_target->getModelName() );
		$alias = $query_target->getAlias();

		if ( !$model->fieldExists($field) ){
			_throw( new Charcoal_InvalidArgumentException("field=[$field]") );
		}

		$field = us($field);

		$override[$field]['update'] = new Charcoal_AnnotationValue( 'update', 'function', array('increment') );

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
	 */
	public function decrementField( $query_target, $data_id, $field ) 
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkInteger( 2, $data_id );
		Charcoal_ParamTrait::checkString( 3, $field );

		$model = $this->getModel( $query_target->getModelName() );
		$alias = $query_target->getAlias();

		if ( !$model->fieldExists($field) ){
			_throw( new Charcoal_InvalidArgumentException("field=[$field]") );
		}

		$field = us($field);

		$override[$field]['update'] = new Charcoal_AnnotationValue( 'update', 'function', array('decrement') );

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
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkInteger( 2, $data_id );
		Charcoal_ParamTrait::checkString( 3, $field );
		Charcoal_ParamTrait::checkScalar( 4, $value );

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
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::checkHashMap( 3, $data );

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

