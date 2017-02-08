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
    const TAG = 'smart_gateway';
    
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
     *    get data source
     *
     *    @return Charcoal_IDataSource        currently selected data source
     */
    public function getDataSource()
    {
        return $this->data_source;
    }

    /**
     *    select data source
     *
     * @param Charcoal_IDataSource $data_source       data source to select
     */
    public function setDataSource( $data_source )
    {
        Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_IDataSource', $data_source );

        $this->data_source = $data_source;
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
        return $this->data_source->popSQLHistory( $throw );
    }

    /**
     *    get all SQL histories
     *
     *    @return array       array of Charcoal_SQLHistory object
     */
    public function getAllSQLHistories()
    {
        return $this->data_source->getAllSQLHistories();
    }

    /**
     *    List models
     *
     * @param Charcoal_Sandbox $sandbox
     * @param int $find_path
     *
     * @return Charcoal_ITableModel[]
     */
    public function listModels( $sandbox, $find_path )
    {
        // get root path of framework,project,web_app
        $dir_framework = Charcoal_ResourceLocator::getFrameworkPath();
        $dir_project = Charcoal_ResourceLocator::getProjectPath();
        $dir_application = Charcoal_ResourceLocator::getApplicationPath();

        // get module root path of framework,project,web_app
        $dir_framework_module = $dir_framework . '/module';
        $dir_project_module = $dir_project . '/module';
        $dir_application_module = $dir_application . '/module';

        $config_target_list = array();

        if ( Charcoal_System::isAnyBitSet( $find_path, Charcoal_EnumFindPath::FIND_PATH_FRAMEWORK ) ){
            $config_target_list[] = $dir_framework . '/config/table_model';
            $config_target_list[] = $dir_framework_module . '/config/table_model';
        }
        if ( Charcoal_System::isAnyBitSet( $find_path, Charcoal_EnumFindPath::FIND_PATH_PROJECT ) ){
            $config_target_list[] = $dir_project . '/config/table_model';
            $config_target_list[] = $dir_project_module . '/config/table_model';
        }
        if ( Charcoal_System::isAnyBitSet( $find_path, Charcoal_EnumFindPath::FIND_PATH_APPLICATION ) ){
            $config_target_list[] = $dir_application . '/config/table_model';
            $config_target_list[] = $dir_application_module . '/config/table_model';
        }

        // find model names in target directory
        $table_model_names = array();
        foreach( $config_target_list as $path ){
            $found_models = $sandbox->getRegistry()->listObjects( $path, 'table_model' );
            $table_model_names = array_merge( $table_model_names, $found_models );
        }

        // convert model name into table model instance
        $table_models = array();
        foreach( $table_model_names as $model_name ){
            $model = $this->getModel( $model_name );
            if ( $model && $model instanceof Charcoal_ITableModel ){
                $table_models[$model_name] = $model;
            }
        }

        return $table_models;
    }

    /**
     *    Reset component
     */
    public function reset()
    {
        if ( $this->data_source ){
            $this->data_source->reset();
        }
    }

    /**
     *    Close connection and destory components
     */
    public function terminate()
    {
        if ( $this->data_source ){
            $this->data_source->disconnect();
        }
    }

    /**
     *    Get last insert id
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
     *    get model
     *
     * @param string $model_name       table model name
     *
     * @return Charcoal_ITableModel
     */
    private function getModel( $model_name )
    {
//        Charcoal_ParamTrait::validateString( 1, $model_name );

        $model_name = us($model_name);

        if ( isset($this->model_cache[$model_name]) ){
            return $this->model_cache[$model_name];
        }

        /**
         * @var Charcoal_ITableModel $model
         */
        $model = $this->sandbox->createObject( $model_name, 'table_model' );

        $model->setModelID( $model_name );

        // set in cache
        $this->model_cache[$model_name] = $model;

        return $model;
    }

    /**
     * select database
     *
     * @param string $database_key
     */
    public function selectDatabase( $database_key = null )
    {
        $this->data_source->selectDatabase( $database_key );
    }

    /**
     * get selected database
     *
     * @return string $database_key
     */
    public function getSelectedDatabase()
    {
        return $this->data_source->getSelectedDatabase();
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

    /*
     *   check if the table exists
     *
     * @param Charcoal_String|string $model_name
     *
     * @return boolean
     */
    public function existsTable( $model_name )
    {
        /** @var Charcoal_IDataSource $ds */
        $ds = $this->data_source;

        try{
            $model = $this->getModel( $model_name );

            $sql = $this->sql_builder->buildExistsTableSQL( $ds->getDatabaseName(), $model );

            $count = $this->queryValue( null, $sql );

            return $count > 0 ? true : false;
        }
        catch ( Exception $e )
        {
            _catch( $e );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }

        return false;
    }

    /**
     *    real implementation of Charcoal_SmartGateway::autoCommit()
     *
     * @param bool $on          If TRUE, transaction will be automatically comitted.
     */
    public function autoCommit( $on )
    {
        $this->data_source->autoCommit( $on );
    }

    /**
     *    real implementation of Charcoal_SmartGateway::beginTrans()
     */
    public function beginTrans()
    {
        $this->data_source->beginTrans();
    }

    /**
     *    execute transaction command: COMMIT
     */
    public function commitTrans()
    {
        $this->data_source->commitTrans();
    }

    /**
     *    execute transaction command: ROLLBACK
     */
    public function rollbackTrans()
    {
        $this->data_source->rollbackTrans();
    }

    /**
     *    real implementation of Charcoal_SmartGateway::execute()
     *
     * @param Charcoal_String|string $comment          comment text
     * @param Charcoal_String|string $sql              SQL statement(placeholders can be included)
     * @param Charcoal_HashMap|array $params           Parameter values for prepared statement
     * @param Charcoal_HashMap|array $driver_options   Driver options
     */
    public function execute( $comment, $sql, $params = NULL, $driver_options = NULL )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateString( 2, $sql );
        Charcoal_ParamTrait::validateHashMap( 3, $params, TRUE );
        Charcoal_ParamTrait::validateHashMap( 4, $driver_options, TRUE );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

        $this->data_source->prepareExecute( $sql, $params, $driver_options );
    }

    /**
     *    real implementation of Charcoal_SmartGateway::query()
     *
     * @param Charcoal_String|string $comment          comment text
     * @param Charcoal_String|string $sql              SQL statement(placeholders can be included)
     * @param Charcoal_HashMap|array $params           Parameter values for prepared statement
     * @param Charcoal_IRecordsetFactory               $recordsetFactory
     * @param Charcoal_HashMap|array $driver_options   Driver options
     *
     * @return array|Charcoal_IRecordset
     */
    public function query( $comment, $sql, $params = NULL, $recordsetFactory = NULL, $driver_options = NULL )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateString( 2, $sql );
        Charcoal_ParamTrait::validateHashMap( 3, $params, TRUE );
        Charcoal_ParamTrait::validateIsA( 4, 'Charcoal_IRecordsetFactory', $recordsetFactory, TRUE );
        Charcoal_ParamTrait::validateHashMap( 5, $driver_options, TRUE );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

        $result = $this->data_source->prepareExecute( $sql, $params, $driver_options );

        if ( $recordsetFactory )
        {
            return $recordsetFactory->createRecordset( $result );
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
     *    real implementation of Charcoal_SmartGateway::queryValue()
     *
     * @param Charcoal_String|string $comment          comment text
     * @param Charcoal_String|string $sql              SQL statement(placeholders can be included)
     * @param Charcoal_HashMap|array $params           Parameter values for prepared statement
     * @param Charcoal_HashMap|array $driver_options   Driver options
     *
     * @return mixed|NULL
     */
    public function queryValue( $comment, $sql, $params = NULL, $driver_options = NULL )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateString( 2, $sql );
        Charcoal_ParamTrait::validateHashMap( 3, $params, TRUE );
        Charcoal_ParamTrait::validateHashMap( 4, $driver_options, TRUE );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

        $result = $this->data_source->prepareExecute( $sql, $params, $driver_options );

        while( $row = $this->data_source->fetchAssoc( $result ) ){
            $value = array_shift($row);
            log_debug( "debug,smart_gateway,sql", "queryValue:$value", self::TAG );
            return $value;
        }

        $this->data_source->free( $result );

        log_warning( "debug,smart_gateway,sql", "smart_gateway", "queryValue: no record" );

        return NULL;
    }

    /**
     *    real implementation of Charcoal_SmartGateway::find()
     *
     * @param Charcoal_String|string $comment       comment text
     * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
     * @param int $options
     * @param Charcoal_SQLCriteria $criteria
     * @param Charcoal_String|string $fields
     * @param Charcoal_IRecordsetFactory $recordsetFactory
     * @param Charcoal_HashMap|array $driver_options   Driver options
     *
     * @return array|Charcoal_IRecordset
     */
    public function find( $comment, $query_target, $options, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateInteger( 3, $options );
        Charcoal_ParamTrait::validateIsA( 4, 'Charcoal_SQLCriteria', $criteria );
        Charcoal_ParamTrait::validateString( 5, $fields, TRUE );
        Charcoal_ParamTrait::validateIsA( 6, 'Charcoal_IRecordsetFactory', $recordsetFactory, TRUE );
        Charcoal_ParamTrait::validateHashMap( 7, $driver_options, TRUE );

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
//            log_debug( "debug,smart_gateway,sql", "SQL: $sql", self::TAG );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

        // パラメータ
        $params = $criteria->getParams();
//            log_debug( "debug,smart_gateway,sql", "params: $params", self::TAG );

        // 実行
        $result = $this->data_source->prepareExecute( $sql, $params, $driver_options );

//            log_debug( "debug,smart_gateway,sql", "executed SQL: $sql", self::TAG );

        if ( $recordsetFactory )
        {
            return $recordsetFactory->createRecordset( $result );
        }
        else{
            $rows = array();
            while( $row = $this->data_source->fetchAssoc( $result ) )
            {
                $rows[] = new Charcoal_HashMap( $row );
            }
            log_debug( "debug,smart_gateway,sql", "rows:" . print_r($rows,true), self::TAG );

            $this->data_source->free( $result );

            return $rows;
        }
    }


    /**
     *    real implementation of Charcoal_SmartGateway::findFirst()
     *
     * @param Charcoal_String|string $comment       comment text
     * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
     * @param Charcoal_SQLCriteria $criteria
     * @param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
     * @param Charcoal_IRecordsetFactory $recordsetFactory
     * @param Charcoal_HashMap|array $driver_options   Driver options
     *
     * @return Charcoal_DTO|NULL
     */
    public function findFirst( $comment, $query_target, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL )
    {
        $criteria->setLimit( 1 );

        $result = $this->find( $comment, $query_target, Charcoal_EnumQueryOption::NO_OPTIONS, $criteria, $fields, $recordsetFactory, $driver_options );

        return $result ? array_shift($result) : NULL;
    }

    /**
     *    real implementation of Charcoal_SmartGateway::findFirstForUpdate()
     *
     * @param Charcoal_String|string $comment       comment text
     * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
     * @param Charcoal_SQLCriteria $criteria
     * @param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
     * @param Charcoal_IRecordsetFactory $recordsetFactory
     * @param Charcoal_HashMap|array $driver_options   Driver options
     *
     * @return Charcoal_DTO|NULL
     */
    public function findFirstForUpdate( $comment, $query_target, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL )
    {
        $criteria->setLimit( 1 );

        $result = $this->find( $comment, $query_target, Charcoal_EnumQueryOption::FOR_UPDATE, $criteria, $fields, $recordsetFactory, $driver_options );

        return $result ? array_shift($result) : NULL;
    }

    /**
     *    real implementation of Charcoal_SmartGateway::findAll()
     *
     * @param Charcoal_String|string $comment       comment text
     * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
     * @param Charcoal_SQLCriteria $criteria
     * @param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
     * @param Charcoal_IRecordsetFactory $recordsetFactory
     * @param Charcoal_HashMap|array $driver_options   Driver options
     *
     * @return array|Charcoal_IRecordset
     */
    public function findAll( $comment, $query_target, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL )
    {
        return $this->find( $comment, $query_target, Charcoal_EnumQueryOption::NO_OPTIONS, $criteria, $fields, $recordsetFactory, $driver_options );
    }

    /**
     *    real implementation of Charcoal_SmartGateway::findAllForUpdate()
     *
     * @param Charcoal_String|string $comment       comment text
     * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
     * @param Charcoal_SQLCriteria $criteria
     * @param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
     * @param Charcoal_IRecordsetFactory $recordsetFactory
     * @param Charcoal_HashMap|array $driver_options   Driver options
     *
     * @return array|Charcoal_IRecordset
     */
    public function findAllForUpdate( $comment, $query_target, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL )
    {
        return $this->find( $comment, $query_target, Charcoal_EnumQueryOption::FOR_UPDATE, $criteria, $fields, $recordsetFactory, $driver_options );
    }

    /**
     *    real implementation of Charcoal_SmartGateway::findAllDistinct()
     *
     * @param Charcoal_String|string $comment       comment text
     * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
     * @param Charcoal_SQLCriteria $criteria
     * @param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
     * @param Charcoal_IRecordsetFactory $recordsetFactory
     * @param Charcoal_HashMap|array $driver_options   Driver options
     *
     * @return array|Charcoal_IRecordset
     */
    public function findAllDistinct( $comment, $query_target, $criteria, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL )
    {
        return $this->find( $comment, $query_target, Charcoal_EnumQueryOption::DISTINCT, $criteria, $fields, $recordsetFactory, $driver_options );
    }

    /**
     *    real implementation of Charcoal_SmartGateway::findAllBy()
     *
     * @param Charcoal_String|string $comment       comment text
     * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
     * @param string $field
     * @param mixed $value
     * @param Charcoal_String|string $fields         comma-separated field list: like 'A,B,C...'
     * @param Charcoal_IRecordsetFactory $recordsetFactory
     * @param Charcoal_HashMap|array $driver_options   Driver options
     *
     * @return array|Charcoal_IRecordset
     */
    public function findAllBy( $comment, $query_target, $field, $value, $fields = NULL, $recordsetFactory = NULL, $driver_options = NULL )
    {
        $field = us( $field );

        $criteria = new Charcoal_SQLCriteria();

        $criteria->setWhere( $field . ' = ?' );
        $criteria->setParams( array( $value ) );

        return $this->findAll( $comment, $query_target, $criteria, $fields, $recordsetFactory, $driver_options );
    }

    /**
     *    real implementation of Charcoal_SmartGateway::findById()
     *
     * @param Charcoal_String|string $comment       comment text
     * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
     * @param int|bool|float|string $id             primary key value of the entity
     *
     * @return array|NULL
     */
    public function findById( $comment, $query_target, $id )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateScalar( 3, $id );

        $model = $this->getModel( $query_target->getModelName() );

        $pk = $model->getPrimaryKey();

        $where_clause = $pk . ' = ?';
        $params = array( ui($id) );

        $criteria = new Charcoal_SQLCriteria( $where_clause, $params );

        $result = $this->findAll( $comment, $query_target, $criteria );

        return $result ? array_shift( $result ) : NULL;
    }

    /**
     *    real implementation of Charcoal_SmartGateway::deleteById()
     *
     * @param Charcoal_String|string $comment       comment text
     * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
     * @param int|bool|float|string $data_id        primary key value of the entity
     *
     * @return int deleted rows
     */
    public function deleteById( $comment, $query_target, $data_id )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateScalar( 3, $data_id );

        $model = $this->getModel( $query_target->getModelName() );
        $alias = $query_target->getAlias();

        $data_id = us( $data_id );

        $pk = us($model->getPrimaryKey());

        $where = us($pk) . ' = ?';
        $params = array( ui($data_id) );

        $criteria = new Charcoal_SQLCriteria( $where, $params );

        $sql = $this->sql_builder->buildDeleteSQL( $model, $alias, $criteria );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

///            log_debug( "debug,smart_gateway,sql", "sql:$sql", self::TAG );
//            log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true), self::TAG );

        $this->data_source->prepareExecute( $sql, $params );
    
        return $this->data_source->numRows();
    }

    /**
     *    real implementation of Charcoal_SmartGateway::deleteByIds()
     *
     * @param Charcoal_String|string $comment       comment text
     * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
     * @param array|Charcoal_Vector $data_ids       array of primary key values for the entity
     *
     * @return int deleted rows
     */
    public function deleteByIds( $comment, $query_target, $data_ids )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateVector( 3, $data_ids );

        $model = $this->getModel( $query_target->getModelName() );
        $alias = $query_target->getAlias();

        $pk = us($model->getPrimaryKey());

        $placeholders = array();
        $params = array();
        foreach( $data_ids as $id ){
            $placeholders[] = '?';
            $params[] = $id;
        }
        $where = us($pk) . ' in (' . implode(',',$placeholders) . ')';

        $criteria = new Charcoal_SQLCriteria( $where, $params );

        $sql = $this->sql_builder->buildDeleteSQL( $model, $alias, $criteria );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

///            log_debug( "debug,smart_gateway,sql", "sql:$sql", self::TAG );
//            log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true), self::TAG );

        $this->data_source->prepareExecute( $sql, $params );
    
        return $this->data_source->numRows();
    }

    /**
     *    real implementation of Charcoal_SmartGateway::deleteBy()
     *
     * @param Charcoal_String|string $comment       comment text
     * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
     * @param Charcoal_String|string $field         field name to query
     * @param Charcoal_Scalar $value                field value to query
     *
     * @return int deleted rows
     */
    public function deleteBy( $comment, $query_target, $field, $value )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateString( 3, $field );
        Charcoal_ParamTrait::validateScalar( 4, $value );

        $value = ($value instanceof Charcoal_Scalar) ? $value->unbox() : $value;

        $where = us($field) . ' = ?';
        $params = array( $value );

        $criteria = new Charcoal_SQLCriteria( $where, $params );

        return $this->deleteAll( $comment, $query_target, $criteria );
    }

    /**
     *    real implementation of Charcoal_SmartGateway::deleteAll()
     *
     * @param Charcoal_String|string $comment       comment text
     * @param Charcoal_QueryTarget $query_target     description about target model, alias, or joins
     * @param Charcoal_SQLCriteria $criteria         criteria object
     *
     * @return int deleted rows
     */
    public function deleteAll( $comment, $query_target, $criteria )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateIsA( 3, 'Charcoal_SQLCriteria', $criteria );

        $model = $this->getModel( $query_target->getModelName() );
        $alias = $query_target->getAlias();

        $sql = $this->sql_builder->buildDeleteSQL( $model, $alias, $criteria );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

        $params = $criteria ? $criteria->getParams() : NULL;

//            log_debug( "debug,smart_gateway,sql", "sql:$sql", self::TAG );
//            log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true), self::TAG );

        $this->data_source->prepareExecute( $sql, $params );

        return $this->data_source->numRows();
    }

    /**
     *    real implementation of Charcoal_SmartGateway::execAggregateQuery()
     *
     * @param Charcoal_String|string $comment       comment text
     * @param int $aggregate_func                   identify aggregate function tpype
     * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
     * @param Charcoal_SQLCriteria|NULL $criteria        criteria object
     * @param Charcoal_String|string|NULL $fields        comma-separated field list: like 'A,B,C...'
     *
     * @return mixed
     */
    private  function execAggregateQuery( $comment, $aggregate_func, $query_target, $criteria = NULL, $fields = NULL )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateInteger( 2, $aggregate_func );
        Charcoal_ParamTrait::validateIsA( 3, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateIsA( 4, 'Charcoal_SQLCriteria', $criteria, TRUE );
        Charcoal_ParamTrait::validateString( 5, $fields, TRUE );

        // default criteria
        if ( $criteria === NULL ){
            $criteria = new Charcoal_SQLCriteria();
        }

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
                                        $fields );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

        $params = $criteria->getParams();

//            log_debug( "debug,smart_gateway,sql", "sql:$sql", self::TAG );
//            log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true), self::TAG );

        // SQL実行
        $result = $this->data_source->prepareExecute( $sql, $params );

        // フェッチ
        $rows = $this->data_source->fetchArray( $result );

        // result
        $result = $rows[0] ? $rows[0] : 0;

        return $result;
    }

    /**
     *    real implementation of Charcoal_SmartGateway::count()
     *
     * @param Charcoal_String|string $comment       comment text
     * @param Charcoal_QueryTarget $query_target     description about target model, alias, or joins
     * @param Charcoal_SQLCriteria|NULL $criteria        criteria object
     * @param Charcoal_String|string|NULL $fields         comma-separated field list: like 'A,B,C...'
     *
     * @return int
     */
    public function count( $comment, $query_target, $criteria = NULL, $fields = NULL )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateIsA( 3, 'Charcoal_SQLCriteria', $criteria, TRUE );
        Charcoal_ParamTrait::validateString( 4, $fields, TRUE );

        if ( $fields === NULL ){
            $fields = '*';
        }

        $result = $this->execAggregateQuery( $comment, Charcoal_EnumSQLAggregateFunc::FUNC_COUNT, $query_target, $criteria, $fields );

        log_debug( "debug,sql,smart_gateway", "COUNT result: $result", self::TAG );

        return intval($result);
    }

    /**
     *    real implementation of Charcoal_SmartGateway::max()
     *
     * @param Charcoal_String|string $comment       comment text
     * @param Charcoal_QueryTarget $query_target     description about target model, alias, or joins
     * @param Charcoal_SQLCriteria|NULL $criteria        criteria object
     * @param Charcoal_String|string|NULL $fields         comma-separated field list: like 'A,B,C...'
     *
     * @return mixed
     */
    public function max( $comment, $query_target, $criteria = NULL, $fields = NULL )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateIsA( 3, 'Charcoal_SQLCriteria', $criteria, TRUE );
        Charcoal_ParamTrait::validateString( 4, $fields, TRUE );

        $result = $this->execAggregateQuery( $comment, Charcoal_EnumSQLAggregateFunc::FUNC_MAX, $query_target, $criteria, $fields );

        log_debug( "debug,sql,smart_gateway", "MAX result: $result", self::TAG );

        return $result;
    }

    /**
     *    real implementation of Charcoal_SmartGateway::min()
     *
     * @param Charcoal_String|string $comment       comment text
     * @param Charcoal_QueryTarget $query_target     description about target model, alias, or joins
     * @param Charcoal_SQLCriteria|NULL $criteria        criteria object
     * @param Charcoal_String|string|NULL $fields         comma-separated field list: like 'A,B,C...'
     *
     * @return mixed
     */
    public function min( $comment, $query_target, $criteria = NULL, $fields = NULL )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateIsA( 3, 'Charcoal_SQLCriteria', $criteria, TRUE );
        Charcoal_ParamTrait::validateString( 4, $fields, TRUE );

        $result = $this->execAggregateQuery( $comment, Charcoal_EnumSQLAggregateFunc::FUNC_MIN, $query_target, $criteria, $fields );

        log_debug( "debug,sql,smart_gateway", "MIN result: $result", self::TAG );

        return $result;
    }

    /**
     *    real implementation of Charcoal_SmartGateway::sum()
     *
     * @param Charcoal_String|string $comment        comment text
     * @param Charcoal_QueryTarget $query_target     description about target model, alias, or joins
     * @param Charcoal_SQLCriteria|NULL $criteria         criteria object
     * @param Charcoal_String|string|NULL $fields         comma-separated field list: like 'A,B,C...'
     *
     * @return mixed
     */
    public function sum( $comment, $query_target, $criteria = NULL, $fields = NULL )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateIsA( 3, 'Charcoal_SQLCriteria', $criteria, TRUE );
        Charcoal_ParamTrait::validateString( 4, $fields, TRUE );

        $result = $this->execAggregateQuery( $comment, Charcoal_EnumSQLAggregateFunc::FUNC_SUM, $query_target, $criteria, $fields );

        log_debug( "debug,sql,smart_gateway", "SUM result: $result", self::TAG );

        return $result;
    }

    /**
     *    real implementation of Charcoal_SmartGateway::avg()
     *
     * @param Charcoal_String|string $comment        comment text
     * @param Charcoal_QueryTarget $query_target     description about target model, alias, or joins
     * @param Charcoal_SQLCriteria|NULL $criteria         criteria object
     * @param Charcoal_String|string|NULL $fields         comma-separated field list: like 'A,B,C...'
     *
     * @return mixed
     */
    public function avg( $comment, $query_target, $criteria = NULL, $fields = NULL )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateIsA( 3, 'Charcoal_SQLCriteria', $criteria, TRUE );
        Charcoal_ParamTrait::validateString( 4, $fields, TRUE );

        $result = $this->execAggregateQuery( $comment, Charcoal_EnumSQLAggregateFunc::FUNC_AVG, $query_target, $criteria, $fields );

        log_debug( "debug,sql,smart_gateway", "AVG result: $result", self::TAG );

        return $result;
    }

    /**
     *    real implementation of Charcoal_SmartGateway::createTable()
     *
     * @param Charcoal_String|string $comment          comment text
     * @param Charcoal_String|string $model_name
     * @param boolean|Charcoal_Boolean $if_not_exists        If TRUE, output SQL includes "IF NOT EXISTS" wuth "CREATE TABLE"
     */
    public function createTable( $comment, $model_name, $if_not_exists = false )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateString( 2, $model_name );
        Charcoal_ParamTrait::validateBoolean( 3, $if_not_exists );

        $model = $this->getModel( $model_name );

        $sql = $this->sql_builder->buildCreateTableSQL( $model, $if_not_exists );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

        $rows_affected = $this->data_source->execute( $sql );

        log_debug( "debug,sql,smart_gateway", "createTable result: $rows_affected", self::TAG );

        return $rows_affected;
    }

    /**
     *    real implementation of Charcoal_SmartGateway::dropTable()
     *
     * @param Charcoal_String|string $comment          comment text
     * @param Charcoal_String|string $model_name
     * @param boolean|Charcoal_Boolean $if_exists        If TRUE, output SQL includes "IF EXISTS" wuth "DROP TABLE"
     */
    public function dropTable( $comment, $model_name, $if_exists = false )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateString( 2, $model_name );
        Charcoal_ParamTrait::validateBoolean( 3, $if_exists );

        $model = $this->getModel( $model_name );

        $sql = $this->sql_builder->buildDropTableSQL( $model, $if_exists );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

        $rows_affected = $this->data_source->execute( $sql );

        log_debug( "debug,sql,smart_gateway", "dropTable result: $rows_affected", self::TAG );

        return $rows_affected;
    }

    /**
     *    real implementation of Charcoal_SmartGateway::truncateTable()
     *
     * @param Charcoal_String|string $comment          comment text
     * @param Charcoal_String|string $model_name
     */
    public function truncateTable( $comment, $model_name )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateString( 2, $model_name );

        $model = $this->getModel( $model_name );

        $sql = $this->sql_builder->buildTruncateTableSQL( $model );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

        $rows_affected = $this->data_source->execute( $sql );

        log_debug( "debug,sql,smart_gateway", "truncateTable result: $rows_affected", self::TAG );

        return $rows_affected;
    }

    /**
     *    real implementation of Charcoal_SmartGateway::save()
     *
     * @param Charcoal_String|string $comment          comment text
     * @param Charcoal_QueryTarget $query_target       description about target model, alias, or joins
     * @param Charcoal_DTO $data                       associative DTO object to insert
     *
     * @return int                    last inserted id
     */
    public function save( $comment, $query_target, $data )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateDTO( 3, $data );

        $new_id = 0;

        try{
            $model = $this->getModel( $query_target->getModelName() );
            $alias = $query_target->getAlias();

            // primary key
            $pk      = $model->getPrimaryKey();
            log_debug( "debug,smart_gateway,sql", "pk:$pk", self::TAG );

            // validate primary key value
            $valid = $model->validatePrimaryKeyValue( $data );
            log_debug( "debug,smart_gateway,sql", "pk valid:$valid", self::TAG );

            if ( $valid ){
                // find entity
                $obj = self::findById( $comment, $query_target, $data->$pk );
                log_debug( "debug,smart_gateway,sql", "found entity:" . print_r($obj,true), self::TAG );
                // if not found, dto is regarded as a new entity
                $is_new = empty($obj);
            }
            else{
                // if promary key value is invalid, dto id rebgarded as a new entity
                $is_new = true;
            }
            log_debug( "debug,smart_gateway,sql", "is_new:$is_new", self::TAG );

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

            $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

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

            log_debug( "debug,smart_gateway,sql", "new_id:$new_id", self::TAG );
        }
        catch ( Exception $e )
        {
            _catch( $e );
            _throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
        }

        return $new_id;
    }

    /**
     *    real implementation of Charcoal_SmartGateway::insert()
     *
     * @param Charcoal_String|string $comment                comment text
     * @param string|Charcoal_QueryTarget $query_target      description about target model, alias, or joins
     * @param Charcoal_DTO $data                             associative array/HashMap/DTO object to insert
     *
     * @return int                    last inserted id
     */
    public function insert( $comment, $query_target, $data )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateHashMapOrDTO( 3, $data );

        $model = $this->getModel( $query_target->getModelName() );
        $alias = $query_target->getAlias();

        list( $sql, $params ) = $this->sql_builder->buildInsertSQL( $model, $alias, $data );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

        $this->data_source->prepareExecute( $sql, $params );

        $sql = $this->sql_builder->buildLastIdSQL();

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

        $result = $this->data_source->prepareExecute( $sql );

        $row = $this->data_source->fetchArray( $result );

        $new_id = $row[0];
        log_debug( "debug,smart_gateway,sql", "new_id:$new_id", self::TAG );

        return $new_id;
    }

    /**
     *    real implementation of Charcoal_SmartGateway::insertAll()
     *
     * @param Charcoal_String|string $comment                comment text
     * @param string|Charcoal_QueryTarget $query_target      description about target model, alias, or joins
     * @param array|Charcoal_Vector $data_set                array of array or DTO value to insert
     *
     * @return integer count of affected rows
     */
    public function insertAll( $comment, $query_target, $data_set )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateVector( 3, $data_set );

        $model = $this->getModel( $query_target->getModelName() );
        $alias = $query_target->getAlias();

        list( $sql, $params ) = $this->sql_builder->buildBulkInsertSQL( $model, $alias, $data_set );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

        $this->data_source->prepareExecute( $sql, $params );

        return $this->data_source->numRows();
    }

    /**
     *    real implementation of Charcoal_SmartGateway::updateFields()
     *
     * @param Charcoal_String|string $comment                comment text
     * @param Charcoal_QueryTarget $query_target     description about target model, alias, or joins
     * @param Charcoal_Integer|int $data_id          identify database entity
     * @param Charcoal_HashMap|array $data           associative array or HashMap object to update
     */
    public function updateFields( $comment, $query_target, $data_id, $data )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateInteger( 3, $data_id );
        Charcoal_ParamTrait::validateHashMap( 4, $data );

        $model = $this->getModel( $query_target->getModelName() );
        $alias = $query_target->getAlias();

        $dto = $data instanceof Charcoal_DTO ? $data :  $model->createDTO( um($data) );

//        log_debug( "debug,smart_gateway,sql", "dto:" . print_r($dto,true), self::TAG );

        $pk = $model->getPrimaryKey();
        $where = "$pk = ?";
        $params = array( ui($data_id) );
        $criteria = new Charcoal_SQLCriteria( $where, $params );

        list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $alias, $dto, $criteria );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

//        log_debug( "debug,smart_gateway,sql", "sql:$sql", self::TAG );
//        log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true), self::TAG );

        // SQL実行
        $this->data_source->prepareExecute( $sql, $params );
    }

    /**
     *    real implementation of Charcoal_SmartGateway::updateFieldNow()
     *
     * @param Charcoal_String|string $comment                comment text
     * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
     * @param Charcoal_Integer|int $data_id                          identify database entity
     * @param Charcoal_String|string $field                         field name to update
     */
    public function updateFieldNow( $comment, $query_target, $data_id, $field )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateInteger( 3, $data_id );
        Charcoal_ParamTrait::validateString( 4, $field );

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

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

//        log_debug( "debug,smart_gateway,sql", "sql:$sql", self::TAG );
//        log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true), self::TAG );

        $this->data_source->prepareExecute( $sql, $params );
    }

    /**
     *    real implementation of Charcoal_SmartGateway::incrementField()
     *
     * @param Charcoal_String|string $comment                comment text
     * @param Charcoal_QueryTarget $query_target       description about target model, alias, or joins
     * @param Charcoal_Integer|int $data_id            identify database entity
     * @param Charcoal_String|string $field            field name to increment
     * @param Charcoal_Integer|int $increment_by       amount of increment
     */
    public function incrementField( $comment, $query_target, $data_id, $field, $increment_by = 1 )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateInteger( 3, $data_id );
        Charcoal_ParamTrait::validateString( 4, $field );
        Charcoal_ParamTrait::validateInteger( 5, $increment_by, TRUE );

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

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

//        log_debug( "debug,smart_gateway,sql", "sql:$sql", self::TAG );
//        log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true), self::TAG );

        $this->data_source->prepareExecute( $sql, $params );
    }

    /**
     *    real implementation of Charcoal_SmartGateway::incrementFieldBy()
     *
     * @param Charcoal_String|string $comment                comment text
     * @param Charcoal_QueryTarget $query_target           description about target model, alias, or joins
     * @param Charcoal_String|string $increment_field            field name to increment
     * @param Charcoal_String|string $query_field            field name to query
     * @param mixed $query_value                   field value to query
     * @param Charcoal_Integer|int $increment_by       amount of increment
     *
     * @return integer
     */
    public function incrementFieldBy( $comment, $query_target, $increment_field, $query_field, $query_value, $increment_by = 1 )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateString( 3, $increment_field );
        Charcoal_ParamTrait::validateString( 4, $query_field );
        Charcoal_ParamTrait::validateScalar( 5, $query_value );
        Charcoal_ParamTrait::validateInteger( 6, $increment_by, TRUE );

        $model = $this->getModel( $query_target->getModelName() );
        $alias = $query_target->getAlias();

        if ( !$model->fieldExists($increment_field) ){
            _throw( new Charcoal_InvalidArgumentException("field=[$increment_field]") );
        }
        if ( !$model->fieldExists($query_field) ){
            _throw( new Charcoal_InvalidArgumentException("field=[$query_field]") );
        }

        $increment_field = us($increment_field);
        $query_field = us($query_field);

        $override[$increment_field]['update'] = new Charcoal_AnnotationValue( 'update', 'function', array('increment', $increment_by) );

        $where = "$query_field = ?";
        $params = array( $query_value );
        $criteria = new Charcoal_SQLCriteria( $where, $params );

        list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $alias, $model->createDTO(), $criteria, $override );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

//        log_debug( "debug,smart_gateway,sql", "sql:$sql", self::TAG );
//        log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true), self::TAG );

        $this->data_source->prepareExecute( $sql, $params );

        return $this->data_source->numRows();
    }

    /**
     *    real implementation of Charcoal_SmartGateway::decrementField()
     *
     * @param Charcoal_String|string $comment                comment text
     * @param Charcoal_QueryTarget $query_target       description about target model, alias, or joins
     * @param Charcoal_Integer|int $data_id            identify database entity
     * @param Charcoal_String|string $field            field name to decrement
     * @param Charcoal_Integer|int $decrement_by       amount of decrement
     */
    public function decrementField( $comment, $query_target, $data_id, $field, $decrement_by = 1 )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateInteger( 3, $data_id );
        Charcoal_ParamTrait::validateString( 4, $field );
        Charcoal_ParamTrait::validateInteger( 5, $decrement_by, TRUE );

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

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

//        log_debug( "debug,smart_gateway,sql", "sql:$sql", self::TAG );
//        log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true), self::TAG );

        $this->data_source->prepareExecute( $sql, $params );
    }

    /**
     *    real implementation of Charcoal_SmartGateway::incrementFieldBy()
     *
     * @param Charcoal_String|string $comment                comment text
     * @param Charcoal_QueryTarget $query_target           description about target model, alias, or joins
     * @param Charcoal_String|string $decrement_field            field name to decrement
     * @param Charcoal_String|string $query_field            field name to query
     * @param mixed $query_value                   field value to query
     * @param Charcoal_Integer|int $decrement_by       amount of decrement
     */
    public function decrementFieldBy( $comment, $query_target, $decrement_field, $query_field, $query_value, $decrement_by = 1 )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateString( 3, $decrement_field );
        Charcoal_ParamTrait::validateString( 4, $query_field );
        Charcoal_ParamTrait::validateScalar( 5, $query_value );
        Charcoal_ParamTrait::validateInteger( 6, $decrement_by, TRUE );

        $model = $this->getModel( $query_target->getModelName() );
        $alias = $query_target->getAlias();

        if ( !$model->fieldExists($decrement_field) ){
            _throw( new Charcoal_InvalidArgumentException("field=[$decrement_field]") );
        }
        if ( !$model->fieldExists($query_field) ){
            _throw( new Charcoal_InvalidArgumentException("field=[$query_field]") );
        }

        $decrement_field = us($decrement_field);
        $query_field = us($query_field);

        $override[$decrement_field]['update'] = new Charcoal_AnnotationValue( 'update', 'function', array('decrement', $decrement_by) );

        $where = "$query_field = ?";
        $params = array( $query_value );
        $criteria = new Charcoal_SQLCriteria( $where, $params );

        list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $alias, $model->createDTO(), $criteria, $override );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

//        log_debug( "debug,smart_gateway,sql", "sql:$sql", self::TAG );
//        log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true), self::TAG );

        $this->data_source->prepareExecute( $sql, $params );
    }

    /**
     *    real implementation of Charcoal_SmartGateway::updateFieldNull()
     *
     * @param Charcoal_String|string $comment                comment text
     * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
     * @param Charcoal_Integer|int $data_id         identify database entity
     * @param Charcoal_String|string $field         field name to set null
     */
    public function updateFieldNull( $comment, $query_target, $data_id, $field )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateInteger( 3, $data_id );
        Charcoal_ParamTrait::validateString( 4, $field );

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

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

//        log_debug( "debug,smart_gateway,sql", "sql:$sql", self::TAG );
//        log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true), self::TAG );

        $this->data_source->prepareExecute( $sql, $params );
    }

    /**
     *    real implementation of Charcoal_SmartGateway::updateField()
     *
     * @param Charcoal_String|string $comment                comment text
     * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
     * @param Charcoal_Integer|int $data_id         identify database entity
     * @param Charcoal_String|string $field         field name to update
     * @param Charcoal_Scalar $value                scalar primitive data to update
     */
    public function updateField( $comment, $query_target, $data_id, $field, $value )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateInteger( 3, $data_id );
        Charcoal_ParamTrait::validateString( 4, $field );
        Charcoal_ParamTrait::validateScalar( 5, $value );

        $model = $this->getModel( $query_target->getModelName() );
        $alias = $query_target->getAlias();

        $dto = $model->createDTO();

        $dto->$field = ($value instanceof Charcoal_Scalar) ? $value->unbox() : $value;

//        log_debug( "debug,smart_gateway,sql", "dto:" . print_r($dto,true) );

//        log_debug( "debug,smart_gateway,sql", "sql:$sql", self::TAG );
//        log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true), self::TAG );

        $pk = $model->getPrimaryKey();
        $where = "$pk = ?";
        $params = array( ui($data_id) );
        $criteria = new Charcoal_SQLCriteria( $where, $params );

        list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $alias, $dto, $criteria );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

//        log_debug( "debug,smart_gateway,sql", "sql:$sql", self::TAG );
//        log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true), self::TAG );

        $this->data_source->prepareExecute( $sql, $params );
    }

    /**
     *    real implementation of Charcoal_SmartGateway::updateFieldBy()
     *
     * @param Charcoal_String|string $comment                comment text
     * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
     * @param Charcoal_String|string $update_field            field name to update
     * @param mixed $update_value                             scalar primitive data to update
     * @param Charcoal_String|string $query_field             field name to query
     * @param mixed $query_value                              field value to query
     *
     * @return integer count of affected rows
     */
    public function updateFieldBy( $comment, $query_target, $update_field, $update_value, $query_field, $query_value )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateString( 3, $update_field );
        Charcoal_ParamTrait::validateScalar( 4, $update_value );
        Charcoal_ParamTrait::validateString( 5, $query_field );
        Charcoal_ParamTrait::validateScalar( 6, $query_value );

        $model = $this->getModel( $query_target->getModelName() );
        $alias = $query_target->getAlias();

        $dto = $model->createDTO();

        $dto->$update_field = ($update_value instanceof Charcoal_Scalar) ? $update_value->unbox() : $update_value;

//        log_debug( "debug,smart_gateway,sql", "dto:" . print_r($dto,true) );

//        log_debug( "debug,smart_gateway,sql", "sql:$sql", self::TAG );
//        log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true), self::TAG );

        $where = "$query_field = ?";
        $params = array( $query_value );
        $criteria = new Charcoal_SQLCriteria( $where, $params );

        list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $alias, $dto, $criteria );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

//        log_debug( "debug,smart_gateway,sql", "sql:$sql", self::TAG );
//        log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true), self::TAG );

        $this->data_source->prepareExecute( $sql, $params );

        return $this->data_source->numRows();
    }

    /**
     *    real implementation of Charcoal_SmartGateway::updateAll()
     *
     * @param Charcoal_QueryTarget $query_target    description about target model, alias, or joins
     * @param Charcoal_SQLCriteria $criteria        criteria object
     * @param Charcoal_HashMap|array $data      associative array or HashMap object to update
     * @param Charcoal_String|string $comment                comment text
     *
     * @return integer count of affected rows
     */
    public function updateAll( $comment, $query_target, $criteria, $data )
    {
        Charcoal_ParamTrait::validateString( 1, $comment , TRUE );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_QueryTarget', $query_target );
        Charcoal_ParamTrait::validateIsA( 3, 'Charcoal_SQLCriteria', $criteria );
        Charcoal_ParamTrait::validateHashMap( 4, $data );

        $model = $this->getModel( $query_target->getModelName() );
        $alias = $query_target->getAlias();

        $dto = $model->createDTO( um($data) );

        list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $alias, $dto, $criteria );

        $sql = !empty($comment) ? $this->sql_builder->prependComment( $sql, $comment ) : $sql;

//        log_debug( "debug,smart_gateway,sql", "sql:$sql", self::TAG );
//        log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true), self::TAG );

        // SQL実行
        $this->data_source->prepareExecute( $sql, $params );

        return $this->data_source->numRows();
    }
}

