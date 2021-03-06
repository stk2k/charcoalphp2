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
class Charcoal_SmartGateway extends Charcoal_CharcoalComponent implements Charcoal_ICharcoalComponent
{
    const TAG = 'smart_gateway';

    const VALUE_NULL = 'null';

    /** @var  Charcoal_SmartGatewayImpl */
    private $impl;

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
     * @param array $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );
        
        $config = new Charcoal_HashMap($config);

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
        $ds = $this->getSandbox()->createObject( $data_source, 'data_source', array(), array(), 'Charcoal_IDataSource' );

        /** @var Charcoal_ISQLBuilder $builder */
        $builder = $this->getSandbox()->createObject( $sql_builder, 'sql_builder', array(), array(), 'Charcoal_ISQLBuilder' );

        $this->impl = new Charcoal_SmartGatewayImpl( $this->getSandbox(), $ds, $builder );
    }

    /**
     *    Reset component
     */
    public function reset()
    {
        $this->impl->reset();
    }

    /**
     *    Close connection and destory components
     */
    public function terminate()
    {
        $this->impl->terminate();
    }

    /**
     *    get data source
     *
     *    @return Charcoal_IDataSource        currently selected data source
     */
    public function getDataSource()
    {
        return $this->impl->getDataSource();
    }

    /**
     *    select data source
     *
     * @param Charcoal_IDataSource $data_source       data source to select
     */
    public function setDataSource( $data_source )
    {
        $this->impl->setDataSource( $data_source );
    }

    /**
     * get selected database
     *
     * @return string $database_key
     */
    public function getSelectedDatabase()
    {
        return $this->impl->getSelectedDatabase();
    }

    /**
     *    get last SQL history
     *
     * @param bool|Charcoal_Boolean $throw   If TRUE, throws Charcoal_StackEmptyException when executed SQL stack is empty.
     *
     *    @return Charcoal_SQLHistory       executed SQL
     */
    public function popSQLHistory( $throw = FALSE )
    {
        return $this->impl->popSQLHistory( $throw );
    }

    /**
     *    get all SQL histories
     *
     *    @return array       array of Charcoal_SQLHistory object
     */
    public function getAllSQLHistories()
    {
        return $this->impl->getAllSQLHistories();
    }

    /**
     *    List models
     *
     * @param int $find_path
     *
     * @return Charcoal_ITableModel[]
     */
    public function listModels( $find_path = Charcoal_EnumFindPath::FIND_PATH_ALL )
    {
        return $this->impl->listModels( $this->getSandbox(), $find_path );
    }

    /**
     *    Get last insert id
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

    /**
     * select database
     *
     * @param string $database_key
     */
    public function selectDatabase( $database_key = null )
    {
        $this->impl->selectDatabase( $database_key );
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

    /*
     *   check if the table exists
     *
     * @param Charcoal_String|string $model_name
     *
     * @return boolean
     */
    public function existsTable( $model_name )
    {
        return $this->impl->existsTable( $model_name );
    }

    /**
     * Set auto commit flag
     *
     * @param Charcoal_Boolean|boolean $on          If TRUE, transaction will be automatically comitted.
     */
    public function autoCommit( $on )
    {
        try{
            log_debug( "debug,smart_gateway,sql", "autoCommit($on) called from:" . Charcoal_System::callerAsString(1), self::TAG );
            $this->impl->autoCommit( $on );
            log_debug( "debug,smart_gateway,sql", "done: autoCommit" );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBAutoCommitException( __METHOD__." Failed.", $e ) );
        }
    }

    /**
     *    execute transaction command: BEGIN TRANSACTION
     */
    public function beginTrans()
    {
        try{
            log_debug( "debug,smart_gateway,sql", "beginTrans called from:" . Charcoal_System::callerAsString(1), self::TAG );
            $this->impl->beginTrans();
            log_debug( "debug,smart_gateway,sql", "done: beginTrans", self::TAG );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBBeginTransactionException( __METHOD__." Failed.", $e ) );
        }
    }

    /**
     *    execute transaction command: COMMIT
     */
    public function commitTrans()
    {
        try{
            log_debug( "debug,smart_gateway,sql", "commitTrans called from:" . Charcoal_System::callerAsString(1), self::TAG );
            $this->impl->commitTrans();
            log_debug( "debug,smart_gateway,sql", "done: commitTrans" );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBCommitTransactionException( __METHOD__." Failed.", $e ) );
        }
    }

    /**
     *    execute transaction command: ROLLBACK
     */
    public function rollbackTrans()
    {
        try{
            $this->impl->rollbackTrans();
            log_debug( "debug,smart_gateway,sql", "done: rollbackTrans" );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBRollbackTransactionException( __METHOD__." Failed.", $e ) );
        }
    }

    /**
     *    update all records matched by criteria
     *
     * @param Charcoal_String|string|NULL $comment                comment text
     * @param string $query_target              description about target model, alias, or joins
     * @param Charcoal_SQLCriteria $criteria    criteria object
     * @param array $data                       associative array or HashMap object to update
     */
    public function updateAll( $comment, $query_target, $criteria, $data )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $this->impl->updateAll( $comment, $query_target, $criteria, $data );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
    }

    /**
     *    update a field value in one record
     *
     * @param Charcoal_String|string|NULL $comment                comment text
     * @param string $query_target        description about target model, alias, or joins
     * @param int $data_id                identify database entity
     * @param Charcoal_String|string      $field
     * @param mixed $value                scalar primitive data to update
     */
    public function updateField( $comment, $query_target, $data_id, $field, $value )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $this->impl->updateField( $comment, $query_target, $data_id, $field, $value );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
    }

    /**
     *    update a field value in selected records
     *
     * @param Charcoal_String|string|NULL $comment            comment text
     * @param string $query_target                            description about target model, alias, or joins
     * @param Charcoal_String|string $update_field            field name to update
     * @param mixed $update_value                             scalar primitive data to update
     * @param Charcoal_String|string $query_field             field name to query
     * @param mixed $query_value                              field value to query
     *
     * @return integer count of affected rows
     */
    public function updateFieldBy( $comment, $query_target, $update_field, $update_value, $query_field, $query_value )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            return $this->impl->updateFieldBy( $comment, $query_target, $update_field, $update_value, $query_field, $query_value );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return 0;
    }

    /**
     *    update a field value by current time
     *
     * @param Charcoal_String|string|NULL $comment                comment text
     * @param string $query_target    description about target model, alias, or joins
     * @param int $data_id            identify database entity
     * @param string $field           field name to update
     */
    public function updateFieldNow( $comment, $query_target, $data_id, $field )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $this->impl->updateFieldNow( $comment, $query_target, $data_id, $field );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
    }

    /**
     *    increment field value
     *
     * @param Charcoal_String|string|NULL $comment                comment text
     * @param Charcoal_String|string $query_target    description about target model, alias, or joins
     * @param Charcoal_Integer|integer $data_id            identify database entity
     * @param Charcoal_String|string $field           field name to increment
     * @param Charcoal_Integer|integer $increment_by       amount of increment
     */
    public function incrementField( $comment, $query_target, $data_id, $field, $increment_by = 1 )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $this->impl->incrementField( $comment, $query_target, $data_id, $field, $increment_by );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
    }

    /**
     *    increment field value by specified field
     *
     * @param Charcoal_String|string|NULL $comment       comment text
     * @param Charcoal_String|string $query_target           description about target model, alias, or joins
     * @param Charcoal_String|string $increment_field            field name to increment
     * @param Charcoal_String|string $query_field            field name to query
     * @param mixed $query_value                   field value to query
     * @param Charcoal_Integer|int $increment_by       amount of increment
     */
    public function incrementFieldBy( $comment, $query_target, $increment_field, $query_field, $query_value, $increment_by = 1 )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $this->impl->incrementFieldBy( $comment, $query_target, $increment_field, $query_field, $query_value, $increment_by );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
    }

    /**
     *    decrement field value
     *
     * @param Charcoal_String|string|NULL $comment                comment text
     * @param Charcoal_String|string $query_target    description about target model, alias, or joins
     * @param Charcoal_Integer|integer $data_id            identify database entity
     * @param Charcoal_String|string $field           field name to decrement
     * @param Charcoal_Integer|integer $decrement_by       amount of decrement
     */
    public function decrementField( $comment, $query_target, $data_id, $field, $decrement_by = 1 )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $this->impl->decrementField( $comment, $query_target, $data_id, $field, $decrement_by );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
    }

    /**
     *    decrement field value by specified field
     *
     * @param Charcoal_String|string|NULL $comment       comment text
     * @param Charcoal_String|string $query_target           description about target model, alias, or joins
     * @param Charcoal_String|string $decrement_field            field name to decrement
     * @param Charcoal_String|string $query_field            field name to query
     * @param mixed $query_value                   field value to query
     * @param Charcoal_Integer|int $decrement_by       amount of decrement
     */
    public function decrementFieldBy( $comment, $query_target, $decrement_field, $query_field, $query_value, $decrement_by = 1 )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $this->impl->decrementFieldBy( $comment, $query_target, $decrement_field, $query_field, $query_value, $decrement_by );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
    }

    /**
     *    update field by null
     *
     * @param Charcoal_String|string|NULL $comment                comment text
     * @param string $query_target    description about target model, alias, or joins
     * @param int $data_id            identify database entity
     * @param string $field           field name to set null
     */
    public function updateFieldNull( $comment, $query_target, $data_id, $field )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $this->impl->updateFieldNull( $comment, $query_target, $data_id, $field );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
    }

    /**
     *    update record with multiple fields
     *
     * @param Charcoal_String|string|NULL $comment       comment text
     * @param string $query_target                       description about target model, alias, or joins
     * @param int $data_id                               identify database entity
     * @param Charcoal_HashMap|array $data               associative array or HashMap object to update
     */
    public function updateFields( $comment, $query_target, $data_id, $data )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $this->impl->updateFields( $comment, $query_target, $data_id, $data );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
    }

    /**
     *    insert DTO into specified table
     *
     * @param Charcoal_String|string|NULL $comment           comment text
     * @param string|Charcoal_QueryTarget $query_target      description about target model, alias, or joins
     * @param Charcoal_DTO $data                             associative array/HashMap/DTO object to insert
     *
     * @return int         new data id
     */
    public function insert( $comment, $query_target, $data )
    {
        $new_id = 0;

        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $new_id = $this->impl->insert( $comment, $query_target, $data );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return $new_id;
    }

    /**
     *    bulk insert
     *
     * @param Charcoal_String|string|NULL $comment                comment text
     * @param string|Charcoal_QueryTarget $query_target      description about target model, alias, or joins
     * @param array|Charcoal_Vector $data_set                array of array or DTO value to insert
     *
     * @return int inserted rows
     */
    public function insertAll( $comment, $query_target, $data_set )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            return $this->impl->insertAll( $comment, $query_target, $data_set );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return 0;
    }

    /**
     *    insert or update DTO into specified table
     *
     * @param Charcoal_String|string|NULL $comment     comment text
     * @param string $query_target                     description about target model, alias, or joins
     * @param Charcoal_DTO $data                       associative array/HashMap/DTO object to update
     *
     * @return int          new data id
     */
    public function save( $comment, $query_target, $data )
    {
        $new_id = 0;

        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $new_id = $this->impl->save( $comment, $query_target, $data );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return $new_id;
    }

    /**
     *    execute command query(INSERT/DELETE/UPDATE)
     *
     * @param Charcoal_String|string|NULL $comment          comment text
     * @param string|Charcoal_String $sql              SQL statement(placeholders can be included)
     * @param array|Charcoal_HashMap $params           Parameter values for prepared statement
     * @param array|Charcoal_HashMap $driver_options   Driver options
     */
    public function execute( $comment, $sql, $params = NULL, $driver_options = NULL )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            $this->impl->execute( $comment, $sql, $params, $driver_options );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
    }

    /**
     *    execute a query and retrieve single value
     *
     * @param string|Charcoal_String $sql              SQL statement(placeholders can be included)
     * @param array|Charcoal_HashMap $params           Parameter values for prepared statement
     * @param array|Charcoal_HashMap $driver_options   Driver options
     * @param Charcoal_String|string|NULL $comment          comment text
     *
     * @return mixed|NULL
     */
    public function queryValue( $comment, $sql, $params = NULL, $driver_options = NULL )
    {
        $value = NULL;

        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            $value = $this->impl->queryValue( $comment, $sql, $params, $driver_options );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return $value;
    }

    /**
     *    execute a query with parameters
     *
     * @param Charcoal_String|string|NULL $comment       comment text
     * @param string|Charcoal_String $sql              SQL statement(placeholders can be included)
     * @param array|Charcoal_HashMap $params           Parameter values for prepared statement
     * @param Charcoal_IRecordsetFactory $recordsetFactory
     * @param array|Charcoal_HashMap $driver_options   Driver options
     *
     * @return array|Charcoal_IRecordset
     */
    public function query( $comment, $sql, $params = NULL, $recordsetFactory = NULL, $driver_options = NULL )
    {
        $result = NULL;

        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            $result = $this->impl->query( $comment, $sql, $params, $recordsetFactory, $driver_options );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return $result;
    }

    /**
     *    find records
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
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            return $this->impl->find( $query_target, $options, $criteria, $fields, $recordsetFactory, $driver_options );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }

        return $ret_val;
    }*/

    /**
     *    Select first record
     *
     * @param Charcoal_String|string|NULL $comment       comment text
     * @param string|Charcoal_String $query_target    description about target model, alias, or joins
     * @param Charcoal_SQLCriteria $criteria          criteria for result set
     * @param string|Charcoal_String $fields          comma separated field list to output
     * @param Charcoal_IRecordsetFactory $recordsetFactory
     * @param Charcoal_HashMap|array $driver_options   Driver options
     *
     * @return Charcoal_DTO|NULL
     */
    public function findFirst( $comment, $query_target, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL )
    {
        $result = NULL;

        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $result = $this->impl->findFirst( $comment, $query_target, $criteria, $fields, $recordsetFactory, $driver_options );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return $result;
    }

    /**
     *    Select first record for updating
     *
     * @param Charcoal_String|string|NULL $comment       comment text
     * @param string|Charcoal_String $query_target    description about target model, alias, or joins
     * @param Charcoal_SQLCriteria $criteria          criteria for result set
     * @param string|Charcoal_String $fields          comma separated field list to output
     * @param Charcoal_IRecordsetFactory $recordsetFactory
     * @param Charcoal_HashMap|array $driver_options   Driver options
     *
     * @return Charcoal_DTO|NULL
     */
    public function findFirstForUpdate( $comment, $query_target, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL )
    {
        $result = NULL;

        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $result = $this->impl->findFirstForUpdate( $comment, $query_target, $criteria, $fields, $recordsetFactory, $driver_options );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return $result;
    }

    /**
     *    Select all records
     *
     * @param Charcoal_String|string|NULL $comment       comment text
     * @param string|Charcoal_String $query_target    description about target model, alias, or joins
     * @param Charcoal_SQLCriteria $criteria          criteria for result set
     * @param string|Charcoal_String $fields          comma separated field list to output
     * @param Charcoal_IRecordsetFactory $recordsetFactory
     * @param array|Charcoal_HashMap $driver_options   Driver options
     *
     * @return Charcoal_DTO[]
     */
    public function findAll( $comment, $query_target, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL )
    {
        $result = NULL;

        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $result = $this->impl->findAll( $comment, $query_target, $criteria, $fields, $recordsetFactory, $driver_options );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return $result;
    }

    /**
     *    Select all records for updating
     *
     * @param Charcoal_String|string|NULL $comment       comment text
     * @param string|Charcoal_String $query_target    description about target model, alias, or joins
     * @param Charcoal_SQLCriteria $criteria          criteria for result set
     * @param string|Charcoal_String $fields          comma separated field list to output
     * @param Charcoal_IRecordsetFactory $recordsetFactory
     * @param array|Charcoal_HashMap $driver_options   Driver options
     *
     * @return Charcoal_DTO[]
     */
    public function findAllForUpdate( $comment, $query_target, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL )
    {
        $result = NULL;

        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $result = $this->impl->findAllForUpdate( $comment, $query_target, $criteria, $fields, $recordsetFactory, $driver_options );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return $result;
    }

    /**
     *    Select unique record
     *
     * @param Charcoal_String|string|NULL $comment       comment text
     * @param string|Charcoal_String $query_target    description about target model, alias, or joins
     * @param Charcoal_SQLCriteria $criteria          criteria for result set
     * @param string|Charcoal_String $fields          comma separated field list to output
     * @param Charcoal_IRecordsetFactory $recordsetFactory
     * @param array|Charcoal_HashMap $driver_options   Driver options
     *
     * @return Charcoal_DTO[]
     */
    public function findAllDistinct( $comment, $query_target, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL )
    {
        $result = NULL;

        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $result = $this->impl->findAllDistinct( $comment, $query_target, $criteria, $fields, $recordsetFactory, $driver_options );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return $result;
    }

    /**
     *    Select all records by field value
     *
     * @param Charcoal_String|string|NULL $comment       comment text
     * @param string|Charcoal_String $query_target    description about target model, alias, or joins
     * @param string|Charcoal_String $field           field name to restrict records
     * @param mixed $value                            field value to restrict records
     * @param string|Charcoal_String $fields          comma separated field list to output
     * @param Charcoal_IRecordsetFactory $recordsetFactory
     * @param array|Charcoal_HashMap $driver_options   Driver options
     *
     * @return Charcoal_DTO[]
     */
    public function findAllBy( $comment, $query_target, $field, $value, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL )
    {
        $result = NULL;

        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $result = $this->impl->findAllBy( $comment, $query_target, $field, $value, $fields, $recordsetFactory, $driver_options );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return $result;
    }

    /**
     *    Select a record by primary key
     *
     * @param Charcoal_String|string|NULL $comment       comment text
     * @param string $query_target    description about target model, alias, or joins
     * @param int|bool|float|string $id             primary key value of the entity
     *
     * @return Charcoal_DTO|NULL
     */
    public function findById( $comment, $query_target, $id )
    {
        $result = NULL;

        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $result = $this->impl->findById( $comment, $query_target, $id );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return $result;
    }

    /**
     *    Remove a record by primary key
     *
     * @param Charcoal_String|string|NULL $comment       comment text
     * @param string $query_target                  description about target model, alias, or joins
     * @param int $id                               identify database entity
     *
     * @return int deleted rows
     */
    public function deleteById( $comment, $query_target, $id )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            return $this->impl->deleteById( $comment, $query_target, $id );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return 0;
    }

    /**
     *    Remove records by primary keys
     *
     * @param Charcoal_String|string|NULL $comment       comment text
     * @param string $query_target                  description about target model, alias, or joins
     * @param array|Charcoal_Vector $ids       array of primary key values for the entity
     *
     * @return int deleted rows
     */
    public function deleteByIds( $comment, $query_target, $ids )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            return $this->impl->deleteByIds( $comment, $query_target, $ids );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return 0;
    }

    /**
     *    Remove all records by specified field
     *
     * @param Charcoal_String|string|NULL $comment       comment text
     * @param string $query_target           description about target model, alias, or joins
     * @param string $field                  field name to query
     * @param mixed $value                   field value to query
     *
     * @return int deleted rows
     */
    public function deleteBy( $comment, $query_target, $field, $value )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            return $this->impl->deleteBy( $comment, $query_target, $field, $value );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return 0;
    }

    /**
     *    Remove all records
     *
     * @param Charcoal_String|string|NULL $comment       comment text
     * @param string $query_target               description about target model, alias, or joins
     * @param Charcoal_SQLCriteria $criteria     criteria object
     *
     * @return int deleted rows
     */
    public function deleteAll( $comment, $query_target, $criteria )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            return $this->impl->deleteAll( $comment, $query_target, $criteria );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return 0;
    }

    /**
     *    apply COUNT aggregate function to specified table
     *
     * @param Charcoal_String|string|NULL $comment           comment text
     * @param Charcoal_String|string $query_target      description about target model, alias, or joins
     * @param Charcoal_SQLCriteria|NULL $criteria            criteria object
     * @param Charcoal_String|string|NULL $fields            fields to be included result set
     *
     * @return int
     */
    public function count( $comment, $query_target, $criteria = NULL, $fields = NULL )
    {
        $result = NULL;

        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $result = $this->impl->count( $comment, $query_target, $criteria, $fields );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return $result;
    }

    /**
     *    apply MAX aggregate function to specified table
     *
     * @param Charcoal_String|string|NULL $comment         comment text
     * @param Charcoal_String|string $query_target    description about target model, alias, or joins
     * @param Charcoal_SQLCriteria|NULL $criteria          criteria object
     * @param Charcoal_String|string|NULL $fields          fields to be included result set
     *
     * @return mixed
     */
    public function max( $comment, $query_target, $criteria = NULL, $fields = NULL )
    {
        $result = NULL;

        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $result = $this->impl->max( $comment, $query_target, $criteria, $fields );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return $result;
    }

    /**
     *    apply MIN aggregate function to specified table
     *
     * @param Charcoal_String|string|NULL $comment          comment text
     * @param Charcoal_String|string $query_target     description about target model, alias, or joins
     * @param Charcoal_SQLCriteria|NULL $criteria           criteria object
     * @param Charcoal_String|string|NULL $fields           fields to be included result set
     *
     * @return mixed
     */
    public function min( $comment, $query_target, $criteria = NULL, $fields = NULL )
    {
        $result = NULL;

        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $result = $this->impl->min( $comment, $query_target, $criteria, $fields );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return $result;
    }

    /**
     *    apply SUM aggregate function to specified table
     *
     * @param Charcoal_String|string|NULL $comment          comment text
     * @param Charcoal_String|string $query_target     description about target model, alias, or joins
     * @param Charcoal_SQLCriteria|NULL $criteria           criteria object
     * @param Charcoal_String|string|NULL $fields           fields to be included result set
     *
     * @return mixed
     */
    public function sum( $comment, $query_target, $criteria = NULL, $fields = NULL )
    {
        $result = NULL;

        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $result = $this->impl->sum( $comment, $query_target, $criteria, $fields );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return $result;
    }

    /**
     *    apply AVG aggregate function to specified table
     *
     * @param Charcoal_String|string|NULL $comment          comment text
     * @param Charcoal_String|string $query_target     description about target model, alias, or joins
     * @param Charcoal_SQLCriteria|NULL $criteria           criteria object
     * @param Charcoal_String|string|NULL $fields           fields to be included result set
     *
     * @return mixed
     */
    public function avg( $comment, $query_target, $criteria = NULL, $fields = NULL )
    {
        $result = NULL;

        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            if ( !($query_target instanceof Charcoal_QueryTarget) ){
                $query_target = new Charcoal_QueryTarget( $query_target );
            }

            $result = $this->impl->avg( $comment, $query_target, $criteria, $fields );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return $result;
    }

    /**
     *    Execute CREATE TABLE sql
     *
     * @param Charcoal_String|string|NULL $comment          comment text
     * @param string $model_name
     * @param boolean|Charcoal_Boolean $if_not_exists        If TRUE, output SQL includes "IF NOT EXISTS" wuth "CREATE TABLE"
     *
     * @return int
     */
    public function createTable( $comment, $model_name, $if_not_exists = false )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            return $this->impl->createTable( $comment, $model_name, $if_not_exists );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return 0;
    }

    /**
     *    Execute DROP TABLE sql
     *
     * @param Charcoal_String|string|NULL $comment          comment text
     * @param string $model_name
     * @param boolean|Charcoal_Boolean $if_exists        If TRUE, output SQL includes "IF EXISTS" wuth
     *
     * @return int
     */
    public function dropTable( $comment, $model_name, $if_exists = false )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            return $this->impl->dropTable( $comment, $model_name, $if_exists );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return 0;
    }

    /**
     *    Execute TRUNCATE TABLE sql
     *
     * @param Charcoal_String|string|NULL $comment          comment text
     * @param string $model_name
     *
     * @return int
     */
    public function truncateTable( $comment, $model_name )
    {
        if ( $comment === NULL ){
            list( $file, $line ) = Charcoal_System::caller(0);
            $comment = basename($file) . '(' . $line . ')';
        }
        try{
            return $this->impl->truncateTable( $comment, $model_name );
        }
        catch ( Exception $e )
        {
            _catch( $e, TRUE );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }
        return 0;
    }

    /**
     *    [Fluent Interface] create fluent interface
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

