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
	const VALUE_NULL = 'null';

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

		/** @var Charcoal_IDataSource $ds */
		$ds = $this->getSandbox()->createObject( $data_source, 'data_source', array(), 'Charcoal_IDataSource' );

		/** @var Charcoal_ISQLBuilder $builder */
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
	 *	get last SQL history
	 *	
	 *	@param bool|Charcoal_Boolean $throw   If TRUE, throws Charcoal_StackEmptyException when executed SQL stack is empty.
	 *	
	 *	@return Charcoal_SQLHistory       executed SQL
	 */
	public function popSQLHistory( $throw = FALSE )
	{
		return $this->impl->popSQLHistory( $throw );
	}

	/**
	 *	get all SQL histories
	 *	
	 *	@return array       array of Charcoal_SQLHistory object
	 */
	public function getAllSQLHistories()
	{
		return $this->impl->getAllSQLHistories();
	}

	/**
	 *	Get last insert id
	 */
	public function getLastInsertId()
	{
		return $this->impl->getLastInsertId();
	}

	/**
	 *  returns count of rows which are affected by previous SQL(DELETE/INSERT/UPDATE)
	 *  
	 *  @return int         count of rows
	 */
	public function numRows()
	{
		return $this->impl->numRows();
	}

	/*
	 *   create recordset factory
	 *
	 * @param integer $fetch_mode    fetch mode(defined at Charcoal_IRecordset::FETCHMODE_XXX)
	 * @param array $options         fetch mode options
	 */
	public function createRecordsetFactory( $fetch_mode = NULL, $options = NULL )
	{
		return $this->impl->createRecordsetFactory( $fetch_mode, $options );
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
	 *	@param Charcoal_String|string      $field
	 *	@param mixed $value                scalar primitive data to update
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
	 *	increment field value
	 *	
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param int $data_id            identify database entity
	 *	@param string $field           field name to increment
	 *	@param int $increment_by       amount of increment
	 */
	public function incrementField( $query_target, $data_id, $field, $increment_by = 1 ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$this->impl->incrementField( $query_target, $data_id, $field, $increment_by );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	decrement field value
	 *	
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param int $data_id            identify database entity
	 *	@param string $field           field name to decrement
	 *	@param int $decrement_by       amount of decrement
	 */
	public function decrementField( $query_target, $data_id, $field, $decrement_by = 1 ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$this->impl->decrementField( $query_target, $data_id, $field, $decrement_by );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	update field by null
	 *	
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param int $data_id            identify database entity
	 *	@param string $field           field name to set null
	 */
	public function updateFieldNull( $query_target, $data_id, $field ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$this->impl->updateFieldNull( $query_target, $data_id, $field );
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
	 *	@param string|Charcoal_QueryTarget $query_target      description about target model, alias, or joins
	 *	@param array $data             associative array/HashMap/DTO object to insert
	 *
	 * @return int         new data id
	 */
	public function insert( $query_target, $data )
	{
		$new_id = 0;
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$new_id = $this->impl->insert( $query_target, $data );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
		return $new_id;
	}

	/**
	 *	bulk insert
	 *	
	 *	@param string|Charcoal_QueryTarget $query_target      description about target model, alias, or joins
	 *	@param array|Charcoal_Vector $data_set                array of array or DTO value to insert
	 */
	public function bulkInsert( $query_target, $data_set )
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$this->impl->bulkInsert( $query_target, $data_set );
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
	 *	@param array|ArrayAccess $data             associative array/HashMap/DTO object to update
	 *
	 * @return int          new data id
	 */
	public function save( $query_target, $data )
	{
		$new_id = 0;
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$new_id = $this->impl->save( $query_target, $data );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
		return $new_id;
	}

	/**
	 *	execute command query(INSERT/DELETE/UPDATE)
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

		try{
			$this->impl->execute( $sql, $params, $driver_options );
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
	 * @param string|Charcoal_String $sql              SQL statement(placeholders can be included)
	 * @param array|Charcoal_HashMap $params           Parameter values for prepared statement
	 * @param array|Charcoal_HashMap $driver_options   Driver options
	 *
	 * @return mixed|NULL
	 */
	public function queryValue( $sql, $params = NULL, $driver_options = NULL )
	{
		Charcoal_ParamTrait::validateString( 1, $sql );
		Charcoal_ParamTrait::validateHashMap( 2, $params, TRUE );
		Charcoal_ParamTrait::validateHashMap( 3, $driver_options, TRUE );

		$value = NULL;
		try{
			$value = $this->impl->queryValue( $sql, $params, $driver_options );
		}
		catch ( Exception $e ) 
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
		return $value;
	}

	/**
	 *	execute a query with parameters
	 *	
	 * @param string|Charcoal_String $sql              SQL statement(placeholders can be included)
	 * @param array|Charcoal_HashMap $params           Parameter values for prepared statement
	 * @param Charcoal_IRecordsetFactory $recordsetFactory
	 * @param array|Charcoal_HashMap $driver_options   Driver options
	 *
	 * @return array|Charcoal_IRecordset
	 */
	public function query( $sql, $params = NULL, $recordsetFactory = NULL, $driver_options = NULL )
	{
		Charcoal_ParamTrait::validateString( 1, $sql );
		Charcoal_ParamTrait::validateHashMap( 2, $params, TRUE );
		Charcoal_ParamTrait::validateIsA( 3, 'Charcoal_IRecordsetFactory', $recordsetFactory, TRUE );
		Charcoal_ParamTrait::validateHashMap( 4, $driver_options, TRUE );

		$result = NULL;
		try{
			$result = $this->impl->query( $sql, $params, $recordsetFactory, $driver_options );
		}
		catch ( Exception $e ) 
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
		return $result;
	}

	/**
	 *	find records
	 *	
	 * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
	 * @param int $options
	 * @param Charcoal_SQLCriteria $criteria          criteria for result set
	 * @param string|Charcoal_String $fields          comma separated field list to output
	 * @param Charcoal_IRecordsetFactory $recordsetFactory
	 * @param array|Charcoal_HashMap $driver_options   Driver options
	 */
	/*
	public function find( $query_target, $options, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL ) 
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateInteger( 2, $options );
		Charcoal_ParamTrait::validateIsA( 3, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::validateString( 4, $fields, TRUE );
		Charcoal_ParamTrait::validateIsA( 5, 'Charcoal_IRecordsetFactory', $recordsetFactory, TRUE );
		Charcoal_ParamTrait::validateHashMap( 6, $driver_options, TRUE );

		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			return $this->impl->find( $query_target, $options, $criteria, $fields, $recordsetFactory, $driver_options );
		}
		catch ( Exception $e ) 
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}

		return $ret_val;
	}*/

	/**
	 *	Select first record
	 *	
	 * @param string|Charcoal_String $query_target    description about target model, alias, or joins
	 * @param Charcoal_SQLCriteria $criteria          criteria for result set
	 * @param string|Charcoal_String $fields          comma separated field list to output
	 *
	 * @return Charcoal_DTO|NULL
	 */
	public function findFirst( $query_target, $criteria, $fields = NULL ) 
	{
		Charcoal_ParamTrait::validateString( 1, 'Charcoal_QueryTarget', $query_target );
		Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::validateString( 3, $fields, TRUE );

		$result = NULL;
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$result = $this->impl->findFirst( $query_target, $criteria, $fields );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
		return $result;
	}

	/**
	 *	Select first record for updating
	 *	
	 * @param string|Charcoal_String $query_target    description about target model, alias, or joins
	 * @param Charcoal_SQLCriteria $criteria          criteria for result set
	 * @param string|Charcoal_String $fields          comma separated field list to output
	 *
	 * @return Charcoal_DTO|NULL
	 */
	public function findFirstForUpdate( $query_target, $criteria, $fields = NULL ) 
	{
		$result = NULL;
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$result = $this->impl->findFirstForUpdate( $query_target, $criteria, $fields );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
		return $result;
	}

	/**
	 *	Select all records
	 *	
	 * @param string|Charcoal_String $query_target    description about target model, alias, or joins
	 * @param Charcoal_SQLCriteria $criteria          criteria for result set
	 * @param string|Charcoal_String $fields          comma separated field list to output
	 * @param Charcoal_IRecordsetFactory $recordsetFactory
	 * @param array|Charcoal_HashMap $driver_options   Driver options
	 *
	 * @return Charcoal_DTO[]
	 */
	public function findAll( $query_target, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL ) 
	{
		Charcoal_ParamTrait::validateString( 1, $query_target );
		Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::validateString( 3, $fields, TRUE );
		Charcoal_ParamTrait::validateIsA( 4, 'Charcoal_IRecordsetFactory', $recordsetFactory, TRUE );
		Charcoal_ParamTrait::validateHashMap( 5, $driver_options, TRUE );

		$result = NULL;
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$result = $this->impl->findAll( $query_target, $criteria, $fields, $recordsetFactory, $driver_options );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
		return $result;
	}

	/**
	 *	Select all records for updating
	 *	
	 * @param string|Charcoal_String $query_target    description about target model, alias, or joins
	 * @param Charcoal_SQLCriteria $criteria          criteria for result set
	 * @param string|Charcoal_String $fields          comma separated field list to output
	 * @param Charcoal_IRecordsetFactory $recordsetFactory
	 * @param array|Charcoal_HashMap $driver_options   Driver options
	 *
	 * @return Charcoal_DTO[]
	 */
	public function findAllForUpdate( $query_target, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL ) 
	{
		Charcoal_ParamTrait::validateString( 1, $query_target );
		Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::validateString( 3, $fields, TRUE );
		Charcoal_ParamTrait::validateIsA( 4, 'Charcoal_IRecordsetFactory', $recordsetFactory, TRUE );
		Charcoal_ParamTrait::validateHashMap( 5, $driver_options, TRUE );

		$result = NULL;
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$result = $this->impl->findAllForUpdate( $query_target, $criteria, $fields, $recordsetFactory, $driver_options );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
		return $result;
	}

	/**
	 *	Select unique record
	 *	
	 * @param string|Charcoal_String $query_target    description about target model, alias, or joins
	 * @param Charcoal_SQLCriteria $criteria          criteria for result set
	 * @param string|Charcoal_String $fields          comma separated field list to output
	 * @param Charcoal_IRecordsetFactory $recordsetFactory
	 * @param array|Charcoal_HashMap $driver_options   Driver options
	 *
	 * @return Charcoal_DTO[]
	 */
	public function findAllDistinct( $query_target, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL  ) 
	{
		Charcoal_ParamTrait::validateString( 1, $query_target );
		Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_SQLCriteria', $criteria );
		Charcoal_ParamTrait::validateString( 3, $fields, TRUE );
		Charcoal_ParamTrait::validateIsA( 4, 'Charcoal_IRecordsetFactory', $recordsetFactory, TRUE );
		Charcoal_ParamTrait::validateHashMap( 5, $driver_options, TRUE );

		$result = NULL;
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$result = $this->impl->findAllDistinct( $query_target, $criteria, $fields, $recordsetFactory, $driver_options );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
		return $result;
	}

	/**
	 *	Select all records by field value
	 *	
	 * @param string|Charcoal_String $query_target    description about target model, alias, or joins
	 * @param string|Charcoal_String $field           field name to restrict records
	 * @param mixed $value                            field value to restrict records
	 * @param string|Charcoal_String $fields          comma separated field list to output
	 * @param Charcoal_IRecordsetFactory $recordsetFactory
	 * @param array|Charcoal_HashMap $driver_options   Driver options
	 *
	 * @return Charcoal_DTO[]
	 */
	public function findAllBy( $query_target, $field, $value, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL )
	{
		Charcoal_ParamTrait::validateString( 1, $query_target );
		Charcoal_ParamTrait::validateString( 2, $field, TRUE );
		Charcoal_ParamTrait::validateScalar( 3, $value, TRUE );
		Charcoal_ParamTrait::validateString( 4, $fields, TRUE );
		Charcoal_ParamTrait::validateIsA( 5, 'Charcoal_IRecordsetFactory', $recordsetFactory, TRUE );
		Charcoal_ParamTrait::validateHashMap( 6, $driver_options, TRUE );

		$result = NULL;
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$result = $this->impl->findAllBy( $query_target, $field, $value, $fields, $recordsetFactory, $driver_options );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
		return $result;
	}

	/**
	 *	Select a record by primary key
	 *	
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param array $id
	 *
	 * @return Charcoal_DTO|NULL
	 */
	public function findById( $query_target, $id ) 
	{
		$result = NULL;
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$result = $this->impl->findById( $query_target, $id );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
		return $result;
	}

	/**
	 *	Remove a record by primary key
	 *	
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param int $id                          identify database entity
	 */
	public function deleteById( $query_target, $id ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$this->impl->deleteById( $query_target, $id );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/**
	 *	Remove records by primary keys
	 *	
	 *	@param string $query_target    description about target model, alias, or joins
	 *	@param array|Charcoal_Vector $data_ids       array of primary key values for the entity
	 */
	public function deleteByIds( $query_target, $ids ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$this->impl->deleteByIds( $query_target, $ids );
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
	 *	@param mixed $value                   field value to query
	 */
	public function deleteBy( $query_target, $field, $value )
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$this->impl->deleteBy( $query_target, $field, $value );
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
	public function deleteAll( $query_target, $criteria ) 
	{
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$this->impl->deleteAll( $query_target, $criteria );
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
	 *	@param Charcoal_String|string $query_target      description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria            criteria object
	 *	@param Charcoal_String|string $fields            fields to be included result set
	 *	@param Charcoal_String|string $comment           comment text
	 *
	 * @return int
	 */
	public function count( $query_target, $criteria, $fields = NULL, $comment = NULL ) 
	{
		$result = NULL;
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$result = $this->impl->count( $query_target, $criteria, $fields, $comment );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
		return $result;
	}

	/**
	 *	apply MAX aggregate function to specified table
	 *	
	 *	@param Charcoal_String|string $query_target    description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria          criteria object
	 *	@param Charcoal_String|string $fields          fields to be included result set
	 *	@param Charcoal_String|string $comment         comment text
	 *
	 * @return mixed
	 */
	public function max( $query_target, $criteria, $fields = NULL, $comment = NULL ) 
	{
		$result = NULL;
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$result = $this->impl->max( $query_target, $criteria, $fields, $comment );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
		return $result;
	}

	/**
	 *	apply MIN aggregate function to specified table
	 *	
	 *	@param Charcoal_String|string $query_target     description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria           criteria object
	 *	@param Charcoal_String|string $fields           fields to be included result set
	 *	@param Charcoal_String|string $comment          comment text
	 *
	 * @return mixed
	 */
	public function min( $query_target, $criteria, $fields = NULL, $comment = NULL ) 
	{
		$result = NULL;
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}

			$result = $this->impl->min( $query_target, $criteria, $fields, $comment );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
		return $result;
	}

	/**
	 *	apply SUM aggregate function to specified table
	 *	
	 *	@param Charcoal_String|string $query_target     description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria           criteria object
	 *	@param Charcoal_String|string $fields           fields to be included result set
	 *	@param Charcoal_String|string $comment          comment text
	 *
	 * @return mixed
	 */
	public function sum( $query_target, $criteria, $fields = NULL, $comment = NULL ) 
	{
		$result = NULL;
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}
			
			$result = $this->impl->sum( $query_target, $criteria, $fields, $comment );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
		return $result;
	}

	/**
	 *	apply AVG aggregate function to specified table
	 *	
	 *	@param Charcoal_String|string $query_target     description about target model, alias, or joins
	 *	@param Charcoal_SQLCriteria $criteria           criteria object
	 *	@param Charcoal_String|string $fields           fields to be included result set
	 *	@param Charcoal_String|string $comment          comment text
	 *
	 * @return mixed
	 */
	public function avg( $query_target, $criteria, $fields = NULL, $comment = NULL ) 
	{
		$result = NULL;
		try{
			if ( !($query_target instanceof Charcoal_QueryTarget) ){
				$query_target = new Charcoal_QueryTarget( $query_target );
			}
			
			$result = $this->impl->avg( $query_target, $criteria, $fields, $comment );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
		return $result;
	}

	/**
	 *	Execute CREATE TABLE sql
	 *	
	 *	@param string $model_name
	 *	@param boolean|Charcoal_Boolean $if_not_exists        If TRUE, output SQL includes "IF NOT EXISTS" wuth "CREATE TABLE"
	 *
	 * @return Charcoal_TableContext
	 */
	public function createTable( $model_name, $if_not_exists = false ) 
	{
		try{
			$this->impl->createTable( $model_name, $if_not_exists );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}

		return new Charcoal_TableContext( $this, $model_name );
	}

	/**
	 *	Execute DROP TABLE sql
	 *	
	 *	@param string $model_name
	 *	@param boolean|Charcoal_Boolean $if_exists        If TRUE, output SQL includes "IF EXISTS" wuth
	 *
	 * @return Charcoal_TableContext
	 */
	public function dropTable( $model_name, $if_exists = false ) 
	{
		try{
			$this->impl->dropTable( $model_name, $if_exists );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}

		return new Charcoal_TableContext( $this, $model_name );
	}

	/**
	 *	Execute TRUNCATE TABLE sql
	 *	
	 *	@param string $model_name
	 *
	 * @return Charcoal_TableContext
	 */
	public function truncateTable( $model_name ) 
	{
		try{
			$this->impl->truncateTable( $model_name );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}

		return new Charcoal_TableContext( $this, $model_name );
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
		Charcoal_ParamTrait::validateString( 1, $fields );

		$context = new Charcoal_QueryContext( $this );

		if ( !empty($fields) ){
			$context->setFields( $fields );
		}

		return new Charcoal_SelectContext( $context );
	}

}

