<?php

/**
* SmartGatewayクラス
*
* PHP version 5
*
* @package    components.charcoal.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_SmartGateway extends Charcoal_CharcoalComponent implements Charcoal_IComponent
{
	private $last_sql;
	private $last_params;

	/**
	 * Constructor
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

		$data_source = $config->getString( 'data_source' );
		$sql_builder = $config->getString( 'sql_builder' );

		log_debug( "smart_gateway, data_source", "data_source=" . $data_source );
		log_debug( "smart_gateway, data_source", "sql_builder=" . $sql_builder );

		$ds = $this->getSandbox()->createObject( $data_source, 'data_source', array(), 'Charcoal_IDataSource' );
		$builder = $this->getSandbox()->createObject( $sql_builder, 'sql_builder', array(), 'Charcoal_ISQLBuilder' );

		$this->impl = new Charcoal_SmartGatewayImpl( $this->getSandbox(), $ds, $builder );
	}

	/**
	 *	Close connection and destory components
	 */
	public function terminate()
	{
		$this->impl->terminate();
	}

	/**
	 *	get data source
	 *	
	 *	@return Charcoal_IDataSource        currently selected data source
	 */
	public function getDataSource()
	{
		return $this->impl->getDataSource();
	}

	/**
	 *	select data source
	 *	
	 *	@param Charcoal_IDataSource $data_source       data source to select
	 */
	public function setDataSource( $data_source )
	{
		$this->impl->setDataSource( $data_source );
	}

	/**
	 *	get last exectuted SQL
	 *	
	 *	@return Charcoal_ExecutedSQL       executed SQL
	 */
	public function popExecutedSQL()
	{
		return $this->impl->popExecutedSQL();
	}

	/**
	 *	get all exectuted SQLs
	 *	
	 *	@return array       executed SQLs
	 */
	public function getAllExecutedSQL()
	{
		return $this->impl->getAllExecutedSQL();
	}

	/**
	 * Set auto commit flag
	 * 
	 * @param bool $on          If TRUE, transaction will be automatically comitted.
	 */
	public function autoCommit( $on )
	{
		try{
			$this->impl->autoCommit( $on );
			log_debug( "debug,smart_gateway,sql", "done: autoCommit" );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBAutoCommitException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	execute transaction command: BEGIN TRANSACTION
	 */
	public function beginTrans()
	{
		try{
			$this->impl->beginTrans();
			log_debug( "debug,smart_gateway,sql", "done: beginTrans" );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBBeginTransactionException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	execute transaction command: COMMIT
	 */
	public function commitTrans()
	{
		try{
			$this->impl->commitTrans();
			log_debug( "debug,smart_gateway,sql", "done: commitTrans" );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBCommitTransactionException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	execute transaction command: ROLLBACK
	 */
	public function rollbackTrans()
	{
		try{
			$this->impl->rollbackTrans();
			log_debug( "debug,smart_gateway,sql", "done: rollbackTrans" );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBRollbackTransactionException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	update all records matched by criteria
	 *	
	 *	@param string $model_name
	 *	@param array $data
	 *	@param Charcoal_SQLCriteria $criteria
	 */
	public  function updateAll( $model_name, $data, $criteria ) 
	{
		try{
			$model = $this->impl->getModel( $model_name );

			$this->impl->updateField( $model, $data_id, $field, $value );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	update field by value
	 *	
	 *	@param string $model_name
	 *	@param string $alias                      table model alias which is specified by $model
	 *	@param int $data_id
	 *	@param Charcoal_Primitive $value
	 */
	public  function updateField( $model_name, $alias, $data_id, $field, $value ) 
	{
		try{
			$model = $this->impl->getModel( $model_name );

			$this->impl->updateField( $model, $alias, $data_id, $field, $value );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	update field by current time
	 *	
	 *	@param string $model_name
	 *	@param string $alias                      table model alias which is specified by $model
	 *	@param int $data_id
	 *	@param array $data
	 */
	public  function updateFieldNow( $model_name, $alias, $data_id, $field ) 
	{
		try{
			$model = $this->impl->getModel( $model_name );

			$this->impl->updateFieldNow( $model, $alias, $data_id, $field );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	update record with multiple fields
	 *	
	 *	@param string $model_name
	 *	@param int $data_id
	 *	@param array $data
	 */
	public  function updateFields( $model_name, $data_id, $data ) 
	{
		try{
			$model = $this->impl->getModel( $model_name );

			$this->impl->updateFields( $model, $data );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	insert DTO into specified table
	 *	
	 *	@param string $model_name
	 *	@param Charcoal_TableDTO $data
	 */
	public  function insert( $model_name, $data )
	{
		try{
			$model = $this->impl->getModel( $model_name );

			return $this->impl->insert( $model, $data );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	insert or update DTO into specified table
	 *	
	 *	@param string $model_name
	 *	@param Charcoal_TableDTO $data
	 */
	public function save( $model_name, $data )
	{
		try{
			$model = $this->impl->getModel( $model_name );

			return $this->impl->save( $model, $data );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	save table DTO
	 *	
	 *	@param string $query_target
	 *	@param int $options
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public function execute( $sql, $params = NULL )
	{
		try{
			$this->impl->execute( $sql, $params );
		}
		catch ( Exception $e ) 
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	execute a query and retrieve single value
	 *	
	 *	@param string $query_target
	 *	@param int $options
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public  function queryValue( $sql, $params = NULL )
	{
		try{
			return $this->impl->queryValue( $sql, $params );
		}
		catch ( Exception $e ) 
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	execute a query with parameters
	 *	
	 *	@param string $query_target
	 *	@param int $options
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public  function query( $sql, $params = NULL )
	{
		try{
			return $this->impl->query( $sql, $params );
		}
		catch ( Exception $e ) 
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	find records
	 *	
	 *	@param string $query_target
	 *	@param int $options
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public function find( $query_target, $options, $criteria, $fields = NULL ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			return $this->impl->find( $query_target, $options, $criteria, $fields );
		}
		catch ( Exception $e ) 
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}

		return $ret_val;
	}

	/**
	 *	Select first record
	 *	
	 *	@param string $query_target
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public  function findFirst( $query_target, $criteria, $fields = NULL ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			return $this->impl->findFirst( $query_target, $criteria, $fields );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	Select first record for updating
	 *	
	 *	@param string $query_target
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public  function findFirstForUpdate( $query_target, $criteria, $fields = NULL ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			return $this->impl->findFirstForUpdate( $query_target, $criteria, $fields );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	Select all records
	 *	
	 *	@param string $query_target
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public  function findAll( $query_target, $criteria, $fields = NULL ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			return $this->impl->findAll( $query_target, $criteria, $fields );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	Select all records for updating
	 *	
	 *	@param string $query_target
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public  function findAllForUpdate( $query_target, $criteria, $fields = NULL ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			return $this->impl->findAllForUpdate( $query_target, $criteria, $fields );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	Select unique record
	 *	
	 *	@param string $query_target
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public  function findDistinct( $query_target, $fields, $criteria, $fields = NULL ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			return $this->impl->findDistinct( $query_target, $criteria, $fields );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	Select all records by field value
	 *	
	 *	@param string $query_target
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public  function findAllBy( $query_target, $field, $value, $fields = NULL )
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			return $this->impl->findAllBy( $query_target, $criteria, $fields );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	Select a record by primary key
	 *	
	 *	@param string $model_name
	 *	@param array $id
	 */
	public  function findById( $model_name, $id ) 
	{
		try{
			$model = $this->impl->getModel( $model_name );

			return $this->impl->findAllBy( $model, $id );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	Remove a record by primary key
	 *	
	 *	@param string $model_name
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public  function destroyById( $model_name, $id ) 
	{
		try{
			$model = $this->impl->getModel( $model_name );

			return $this->impl->destroyById( $model, $id );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	Remove all records by specified field
	 *	
	 *	@param string $model_name
	 *	@param string $field
	 *	@param Charcoal_Primitive $value
	 */
	public  function destroyBy( $model_name, $field, $value )
	{
		try{
			$model = $this->impl->getModel( $model_name );

			return $this->impl->destroyBy( $model, $field, $value );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	Remove all records
	 *	
	 *	@param string $model_name
	 *	@param Charcoal_SQLCriteria $criteria
	 */
	public  function destroyAll( $model_name, $criteria ) 
	{
		try{
			$model = $this->impl->getModel( $model_name );

			return $this->impl->destroyAll( $model, $criteria );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	Execute aggregate SQL
	 *	
	 *	@param int $aggregate_func
	 *	@param string $query_target
	 *	@param Charcoal_SQLCriteria $criteria
	 */
	private  function execAggregateQuery( $aggregate_func, $query_target, $criteria, $fields = NULL ) 
	{
		try{
			$model = $this->impl->getModel( $model_name );

			return $this->impl->execAggregateQuery( $aggregate_func, $query_target, $criteria, $fields );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	apply COUNT aggregate function to specified table
	 *	
	 *	@param Charcoal_QueryTarget $query_target
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public  function count( $query_target, $criteria, $fields = NULL ) 
	{
		try{
			return $this->impl->count( $query_target, $criteria, $fields );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	apply MAX aggregate function to specified table
	 *	
	 *	@param Charcoal_QueryTarget $query_target
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public  function max( $query_target, $criteria, $fields = NULL  ) 
	{
		try{
			return $this->impl->max( $query_target, $criteria, $fields );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	apply MIN aggregate function to specified table
	 *	
	 *	@param Charcoal_QueryTarget $query_target
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public  function min( $query_target, $criteria, $fields ) 
	{
		try{
			return $this->impl->min( $query_target, $criteria, $fields );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	apply SUM aggregate function to specified table
	 *	
	 *	@param Charcoal_QueryTarget $query_target
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public  function sum( $query_target, $criteria, $fields ) 
	{
		try{
			return $this->impl->sum( $query_target, $criteria, $fields );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	apply AVG aggregate function to specified table
	 *	
	 *	@param Charcoal_QueryTarget $query_target
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public  function avg( $query_target, $criteria, $fields ) 
	{
		try{
			return $this->impl->avg( $query_target, $criteria, $fields );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	Execute CREATE DATABASE sql
	 *	
	 *	@param string $db_name
	 *	@param string $charset
	 */
	public  function createDatabase( $db_name, $charset ) 
	{
		try{
			$this->impl->createDatabase( $db_name, $charset );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	Execute CREATE TABLE sql
	 *	
	 *	@param string $model_name
	 */
	public  function createTable( $model_name ) 
	{
		try{
			$this->impl->createTable( $model_name );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	[Fluent Interface] create fluent interface
	 *
	 * @param string $fields    field list(comma separated string) for SELECT clause
	 *
	 * @return Charcoal_SelectContext    select context
	 */
	public  function select( $fields ) 
	{
		Charcoal_ParamTrait::checkString( 1, $fields );

		$context = new Charcoal_QueryContext( $this );

		if ( !$fields->isEmpty() ){
			$fields = $fields->split( s(',') );
			$context->setFields( $fields );
		}

		return new Charcoal_SelectContext( $context );
	}
}

