<?php

/**
* SmartGatewayクラス
*
* PHP version 5
*
* @package    component.charcoal.db
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
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

		if ( empty($data_source) ){
			_throw( new Charcoal_DBDataSourceException( 'data source is not specified.' ) );
		}
		if ( empty($sql_builder) ){
			_throw( new Charcoal_DBDataSourceException( 'sql builder is not specified.' ) );
		}

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
	 *	@param string $query_target              description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria    criteria object
	 *	@param array $data                       associative array or HashMap object to update
	 */
	public function updateAll( $query_target, $criteria, $data ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$this->impl->updateAll( $query_target, $criteria, $data );
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
	 *	@param string $query_target        description about target model, alias, or joins
	 *	@param int $data_id                identify database entity
	 *	@param Charcoal_Scalar $value      scalar primitive data to update
	 */
	public function updateField( $query_target, $data_id, $field, $value ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$this->impl->updateField( $query_target, $data_id, $field, $value );
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
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param int $data_id            identify database entity
	 *	@param string $field           field name to update
	 */
	public function updateFieldNow( $query_target, $data_id, $field ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$this->impl->updateFieldNow( $query_target, $data_id, $field );
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
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param int $data_id            identify database entity
	 *	@param array $data             associative array or HashMap object to update
	 */
	public function updateFields( $query_target, $data_id, $data ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$this->impl->updateFields( $query_target, $data );
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
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param array $data             associative array or HashMap object to insert
	 */
	public function insert( $query_target, $data )
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			return $this->impl->insert( $query_target, $data );
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
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param array $data             associative array or HashMap object to update
	 */
	public function save( $query_target, $data )
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			return $this->impl->save( $query_target, $data );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	execute command query(INSERT/DELETE/UPDATE)
	 *	
	 *	@param string $query_target    description about target model, alias, or joins
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
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param int $options
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public function queryValue( $sql, $params = NULL )
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
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param int $options
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public function query( $sql, $params = NULL )
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
	 *	@param string $query_target    description about target model, alias, or joins
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
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public function findFirst( $query_target, $criteria, $fields = NULL ) 
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
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public function findFirstForUpdate( $query_target, $criteria, $fields = NULL ) 
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
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public function findAll( $query_target, $criteria, $fields = NULL ) 
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
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public function findAllForUpdate( $query_target, $criteria, $fields = NULL ) 
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
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public function findDistinct( $query_target, $fields, $criteria, $fields = NULL ) 
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
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria
	 *	@param array $fields
	 */
	public function findAllBy( $query_target, $field, $value, $fields = NULL )
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
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param array $id
	 */
	public function findById( $model_name, $id ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			return $this->impl->findById( $query_target, $id );
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
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param int $data_id                          identify database entity
	 */
	public function destroyById( $query_target, $id ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			return $this->impl->destroyById( $query_target, $id );
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
	 *	@param string $query_target           description about target model, alias, or joins
	 *	@param string $field                  field name to query
	 *	@param Charcoal_Scalar $value         field value to query
	 */
	public function destroyBy( $query_target, $field, $value )
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			return $this->impl->destroyBy( $query_target, $field, $value );
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
	 *	@param string $query_target               description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria     criteria object
	 */
	public function destroyAll( $model_name, $criteria ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			return $this->impl->destroyAll( $query_target, $criteria );
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
	 *	@param int $aggregate_func              identify aggregate function tpype
	 *	@param strin $query_target              description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria   criteria object
	 *	@param string $fields                   fields to be included result set
	 */
	private  function execAggregateQuery( $aggregate_func, $query_target, $criteria, $fields = NULL ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

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
	 *	@param string $query_target              description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria    criteria object
	 *	@param string $fields                    fields to be included result set
	 */
	public function count( $query_target, $criteria, $fields = NULL ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

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
	 *	@param string $query_target             description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria   criteria object
	 *	@param string $fields                   fields to be included result set
	 */
	public function max( $query_target, $criteria, $fields = NULL  ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

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
	 *	@param string $query_target               description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria     criteria object
	 *	@param string $fields                     fields to be included result set
	 */
	public function min( $query_target, $criteria, $fields ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

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
	 *	@param string $query_target               description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria     criteria object
	 *	@param string $fields                    fields to be included result set
	 */
	public function sum( $query_target, $criteria, $fields ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}
			
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
	 *	@param string $query_target               description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria     criteria object
	 *	@param string $fields                    fields to be included result set
	 */
	public function avg( $query_target, $criteria, $fields ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}
			
			return $this->impl->avg( $query_target, $criteria, $fields );
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
	public function createTable( $model_name ) 
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

	/**
	 *	[Fluent Interface] create fluent interface
	 *
	 * @param string $fields    field list(comma separated string) for SELECT clause
	 *
	 * @return Charcoal_SelectContext    select context
	 */
	public function select( $fields ) 
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

