<?php
/**
* data source for PDO
*
* PHP version 5
*
* @package    objects.data_sources
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_PDODbDataSource extends Charcoal_AbstractDataSource
{
    const TAG = 'pdo_db_datasource';

    const DEFAULT_DATABASE_KEY = 'default';

    private $connections;

    private $selected_db;

    private $backend;
    private $user;
    private $password;
    private $db_name;
    private $server;
    private $port;
    private $charset;
    private $autocommit;
    private $set_names;
    private $buffered_query;

    private $command_id;

    private $sql_histories;

    private $num_rows;

    private $saved_config;

    /*
     *  Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->connections    = array();
        $this->command_id     = 0;

        $this->sql_histories = new Charcoal_Stack();
    }

    /**
     * Initialize instance
     *
     * @param Charcoal_Config $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );

        $default_config = um( $config->getHashMap( self::DEFAULT_DATABASE_KEY, array() ) );

        if ( empty($default_config) ){
            _throw( new Charcoal_DataSourceConfigException('', 'Maybe missing default section in data source config?') );
        }

        $this->loadDatabaseConfig( $default_config );

        $this->selected_db = self::DEFAULT_DATABASE_KEY;

        // back up config
        $this->saved_config = $config;
    }

    /**
     * select database
     *
     * @param string $database_key
     */
    public function selectDatabase( $database_key = null )
    {
        if ( !$database_key ){
            $database_key = self::DEFAULT_DATABASE_KEY;
        }

        /** @var Charcoal_Config $config */
        $config = $this->saved_config;

        $db_config = um( $config->getHashMap( $database_key, array() ) );

        if ( empty($db_config) ){
            _throw( new Charcoal_DataSourceConfigException('', 'Missing database config: ' . $database_key) );
        }

        $this->loadDatabaseConfig( $db_config );

        $this->selected_db = $database_key;
    }

    /**
     * get selected database
     *
     * @return string $database_key
     */
    public function getSelectedDatabase()
    {
        return $this->selected_db;
    }

    /**
     * get active connection
     *
     * @return PDO
     */
    private function getConnection()
    {
        return isset($this->connections[$this->selected_db]) ? $this->connections[$this->selected_db] : null;
    }

    /**
     * load database config
     *
     * @param array $config
     */
    public function loadDatabaseConfig( $config )
    {
        $this->backend   = isset($config['backend']) ? $config['backend'] : '';
        $this->user      = isset($config['user']) ? $config['user'] : '';
        $this->password  = isset($config['password']) ? $config['password'] : '';
        $this->db_name   = isset($config['db_name']) ? $config['db_name'] : '';
        $this->server    = isset($config['server']) ? $config['server'] : '';
        $this->port      = isset($config['port']) ? $config['port'] : '';
        $this->charset   = isset($config['charset']) ? $config['charset'] : '';
        $this->autocommit = isset($config['autocommit']) ? $config['autocommit'] : TRUE;
        $this->set_names  = isset($config['set_names']) ? $config['set_names'] : FALSE;
        $this->buffered_query  = isset($config['buffered_query']) ? $config['buffered_query'] : TRUE;

        if ( strlen($this->backend) === 0 ){
            _throw( new Charcoal_ComponentConfigException( 'backend', 'mandatory' ) );
        }
        if ( strlen($this->user) === 0 ){
            _throw( new Charcoal_ComponentConfigException( 'user', 'mandatory' ) );
        }
        if ( strlen($this->db_name) === 0 ){
            _throw( new Charcoal_ComponentConfigException( 'db_name', 'mandatory' ) );
        }
        if ( strlen($this->server) === 0 ){
            _throw( new Charcoal_ComponentConfigException( 'server', 'mandatory' ) );
        }

        if ( $this->getSandbox()->isDebug() )
        {
            log_debug( 'data_source', "backend=" . $this->backend, self::TAG );
            log_debug( 'data_source', "user=" . $this->user, self::TAG );
            log_debug( 'data_source', "password=" . $this->password, self::TAG );
            log_debug( 'data_source', "db_name=" . $this->db_name, self::TAG );
            log_debug( 'data_source', "server=" . $this->server, self::TAG );
            log_debug( 'data_source', "port=" . $this->port, self::TAG );
            log_debug( 'data_source', "charset=" . $this->charset, self::TAG );
            log_debug( 'data_source', "autocommit=" . $this->autocommit, self::TAG );
            log_debug( 'data_source', "set_names=" . $this->set_names, self::TAG );
            log_debug( 'data_source', "buffered_query=" . $this->buffered_query, self::TAG );
        }
    }

    /**
     *    get last SQL history
     *
     *    @param bool|Charcoal_Boolean $throw   If TRUE, throws Charcoal_StackEmptyException when executed SQL stack is empty.
     *
     *    @return Charcoal_SQLHistory       executed SQL
     */
    public function popSQLHistory( $throw = FALSE )
    {
        $throw = b($throw);

        $item = NULL;
        try{
            $item = $this->sql_histories->pop();
        }
        catch( Charcoal_StackEmptyException $ex ){

            _catch( $ex );

            if ( $throw->isTrue() ){
                _throw( $ex );
            }

        }
        return $item;
    }

    /**
     *    get all SQL histories
     *
     *    @return array       array of Charcoal_SQLHistory object
     */
    public function getAllSQLHistories()
    {
        return $this->sql_histories->getAll();
    }

    /*
     *    接続済みか
     */
    public function isConnected()
    {
        return $this->getConnection() != null;
    }

    /*
     *    バックエンドを取得
     */
    public function getBackend()
    {
        return $this->backend;
    }

    /*
     *    接続先サーバを取得
     */
    public function getServer()
    {
        return $this->server;
    }

    /*
     *    database server port no
     */
    public function getPort()
    {
        return $this->port;
    }


    /*
     *    接続ユーザ名を取得
     */
    public function getUser()
    {
        return $this->user;
    }

    /*
     *    接続パスワードを取得
     */
    public function getPassword()
    {
        return $this->password;
    }

    /*
     *    接続先データベース名を取得
     */
    public function getDatabaseName()
    {
        return $this->db_name;
    }

    /*
     *    接続時の文字コードを取得
     */
    public function getCharacterSet()
    {
        return $this->charset;
    }

    /*
     *    自動コミット機能をON/OFF
     *
     * @param boolean|Charcoal_Boolean $on
     */
    public function autoCommit( $on )
    {
        //log_debug( "transaction,sql", "autoCommit($on) called from:" . Charcoal_System::callerAsString(1), self::TAG );
        $on = ub($on);
        try {
            Charcoal_ParamTrait::validateBoolean( 1, $on );

            // 接続処理
            $this->connect();

            $this->getConnection()->setAttribute( PDO::ATTR_AUTOCOMMIT, $on );

        }
        catch ( Exception $e )
        {
            _catch( $e );
            _throw( new Charcoal_DBDataSourceException( __METHOD__." Failed.", $e ) );
        }
    }

    /*
     *    トランザクションを開始
     */
    public function beginTrans()
    {
        //log_debug( "transaction,sql", "beginTrans called from:" . Charcoal_System::callerAsString(1), self::TAG );
        try {
            // 接続処理
            $this->connect();

            $this->getConnection()->beginTransaction();
        }
        catch ( Exception $e )
        {
            _catch( $e );
            _throw( new Charcoal_DBDataSourceException( __METHOD__." Failed.", $e ) );
        }
    }

    /*
     *    コミットを発行
     */
    public function commitTrans()
    {
        //log_debug( "transaction,sql", "commitTrans called from:" . Charcoal_System::callerAsString(1), self::TAG );
        try {
            // 接続処理
            $this->connect();

            $this->getConnection()->commit();
        }
        catch ( Exception $e )
        {
            _catch( $e );
            _throw( new Charcoal_DBDataSourceException( __METHOD__." Failed.", $e ) );
        }
    }

    /*
     *    ロールバックを発行
     */
    public function rollbackTrans()
    {
//        log_debug( "transaction,sql", "transaction", "rollbackTrans called from:" . print_r( Charcoal_System::caller(1), true ) );
        try {
            // 接続処理
            $this->connect();

            $this->getConnection()->rollback();
        }
        catch ( Exception $e )
        {
            _catch( $e );
            _throw( new Charcoal_DBDataSourceException( __METHOD__." Failed.", $e ) );
        }
    }

    /*
     *  conenct database
     *
     * @param boolean $force
     *
     * @return PDO
     */
    public function connect( $force = FALSE )
    {
        $DSN = NULL;

        // if conection exists, do nothing
        $conn = $this->getConnection();
        if ( $conn && !$force ){
            return $conn;
        }

        $backend   = $this->backend;
        $user      = $this->user;
        $password  = $this->password;
        $db_name   = $this->db_name;
        $port      = $this->port;
        $server    = $this->server;
        $charset   = $this->charset;

        try{
            $charset_db = NULL;
            if ( !$this->set_names && !empty($this->charset) ){
                $db_charset_map = array(
                        'utf8' => 'utf8',
                        'ujis' => 'ujis',
                        'sjis' => 'sjis',
                    );
                $charset_db = isset($db_charset_map[$charset]) ? $db_charset_map[$charset] : NULL;
            }
            $port = !empty($port) ? "port={$port};" : '';
            $charset = $charset_db ? "charset={$charset_db};" : '';
            $DSN = "$backend:host=$server;{$port}dbname=$db_name;{$charset}";

            log_info( 'debug, sql, data_source', "DSN=[$DSN]", self::TAG );

            $options = array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_AUTOCOMMIT => $this->autocommit,
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => $this->buffered_query,
                );
            log_info( 'debug, sql, data_source', 'driver options:' . print_r($options, true), self::TAG );

            $th_connect = Charcoal_Benchmark::start();

            $pdo = new PDO( $DSN, $user, $password, $options );

            $bench_score = Charcoal_Benchmark::stop( $th_connect );
            log_debug( 'data_source,sql,debug', sprintf("connected in [%0.4f] msec",$bench_score), self::TAG );

            $this->connections[$this->selected_db] = $pdo;

            log_info( 'debug, sql, data_source', "connected database: DSN=[$DSN]", self::TAG );

            if ( $this->set_names ){
                switch( strtolower($this->charset) ){
                    case 'utf8':    $this->_query( s('SET NAMES `utf8`') );        break;
                    case 'ujis':    $this->_query( s('SET NAMES `ujis`') );        break;
                    case 'sjis':    $this->_query( s('SET NAMES `sjis`') );        break;
                    default:
                        _throw( new Charcoal_DataSourceConfigException( 'charset', "invalid charset: $charset" ) );
                }
            }
            return $pdo;
        }
        catch ( Exception $e )
        {
            _catch( $e );

            log_error( 'data_source,sql,debug', __METHOD__ . " failed: DSN=[$DSN]", self::TAG );

            _throw( new Charcoal_DBConnectException( __METHOD__ . " failed: DSN=[$DSN]", $e ) );
        }
        return null;
    }

    /*
     *  clean up connections and its own data
     */
    public function reset()
    {
        $this->connections    = array();
        $this->command_id     = 0;

        //$this->selected_db = null;

        $this->command_id = null;

        $this->sql_histories = new Charcoal_Stack();

        $this->num_rows = null;
    }

    /*
     *  disconnect from database
     */
    public function disconnect()
    {
        // if connection does not exists, do nothing.
        $conn = $this->getConnection();
        if ( !$conn ){
            return;
        }

        // remove connection map entry
        unset($this->connections[$this->selected_db]);
    }

    /**
     *    Prepare for statement and execute query
     *
     * @param string|Charcoal_String $sql              SQL statement(placeholders can be included)
     * @param array|Charcoal_HashMap $params           Parameter values for prepared statement
     * @param array|Charcoal_HashMap $driver_options   Driver options
     *
     * @return PDOStatement
     */
    private function _prepareExecute( $sql, $params, $driver_options )
    {
        Charcoal_ParamTrait::validateString( 1, $sql );
        Charcoal_ParamTrait::validateHashMap( 2, $params, TRUE );
        Charcoal_ParamTrait::validateHashMap( 3, $driver_options, TRUE );

        $params = um( $params );
        $driver_options = um( $driver_options );

        $params = $params ? $params : array();
        $driver_options = $driver_options ? $driver_options : array();

        $timer_handle = Charcoal_Benchmark::start();

        $command_id = $this->command_id++;

        $params_disp = $params ? implode( ',' , $params ) :'';

        log_debug( 'data_source,sql,debug', "[ID]$command_id [SQL]$sql", self::TAG );
        log_debug( 'data_source,sql,debug', "[ID]$command_id [params]$params_disp", self::TAG );

        /** @var PDOStatement $stmt */
        $stmt = $this->getConnection()->prepare( $sql, $driver_options );

        $this->sql_histories->push( new Charcoal_SQLHistory($sql, $params) );

        $success = $stmt->execute( $params );

        if ( !$success ){
            list( $sqlstate, $err_code, $err_msg ) = $stmt->errorInfo();
            $msg = "PDO#execute failed. [ID]$command_id [SQL]$sql [params]$params_disp [SQLSTATE]$sqlstate [ERR_CODE]$err_code [ERR_MSG]$err_msg";
            log_error( 'data_source,sql,debug', "...FAILED: $msg", self::TAG );
            _throw( new Charcoal_DBDataSourceException( $msg ) );
        }

        $this->num_rows = $rows = $stmt->rowCount();
        log_info( 'data_source,sql,debug', "[ID]$command_id ...success(numRows=$rows)", self::TAG );

        // ログ
        $elapse = Charcoal_Benchmark::stop( $timer_handle );
        log_debug( 'data_source,sql,debug', "[ID]$command_id _prepareExecute() end. time=[$elapse]msec.", self::TAG );

        // SQL benchmark
        ini_set('serialize_precision', 16);
        log_debug( 'sql_bench', var_export(array($sql, $rows, $elapse), true), self::TAG );

        return $stmt;
    }

    /**
     *  Execute a SQL and return statement object
     *
     * @param string $sql
     *
     * @return PDOStatement
     */
    private function _query( $sql )
    {
        $sql = us( $sql );

        $command_id = $this->command_id++;

        $this->sql_histories->push( new Charcoal_SQLHistory($sql) );

        $stmt = $this->getConnection()->query( $sql );

        log_info( 'data_source,sql,debug', "query executed: [ID]$command_id [SQL]$sql", self::TAG );

        return $stmt;
    }

    /**
     *   Execute an SQL statement and return the number of affected rows
     *
     * @param string $sql
     *
     * @return int
     */
    private function _exec( $sql )
    {
        $sql = us( $sql );

        $command_id = $this->command_id++;

        log_info( 'data_source,sql,debug', "[ID]$command_id [SQL]$sql", self::TAG );

        $this->sql_histories->push( new Charcoal_SQLHistory($sql) );

        $rows_affected = $this->getConnection()->exec( $sql );

        log_info( 'data_source,sql,debug', "execute SQL: [ID]$command_id [SQL]$sql [rows]$rows_affected", self::TAG );

        return $rows_affected;
    }

    /**
     *  Execute a SQL and return statement object
     *
     * @param string $sql
     *
     * @return PDOStatement
     */
    public function query( $sql )
    {
        // 接続処理
        $this->connect();

        $this->sql_histories->push( new Charcoal_SQLHistory($sql) );

        // SQLを実行して結果セットを得る
        $stmt = $this->_query( $sql );

        // 結果セットを返却
        return $stmt;
    }

    /**
     *   Execute an SQL statement and return the number of affected rows
     *
     * @param string $sql
     *
     * @return int
     */
    public function execute( $sql )
    {
        // 接続処理
        $this->connect();

        $this->sql_histories->push( new Charcoal_SQLHistory($sql) );

        // SQLを実行
        return $this->_exec( $sql );
    }

    /**
     *    Prepare for statement and execute query
     *
     * @param string|Charcoal_String $sql              SQL statement(placeholders can be included)
     * @param array|Charcoal_HashMap $params           Parameter values for prepared statement
     * @param array|Charcoal_HashMap $driver_options   Driver options
     *
     * @return PDOStatement
     */
    public function prepareExecute( $sql, $params = NULL, $driver_options = NULL )
    {
        $result = null;

        try {
            // 接続処理
            $this->connect();

            // statementの実行
            $result = $this->_prepareExecute( $sql, $params, $driver_options );
        }
        catch ( Exception $e )
        {
            _catch( $e );

            $msg  = __METHOD__ . ' failed:';
            $msg .= ' [SQL]' . $sql;
            $msg .= ' [params]' . ($params ? v($params)->join(',',TRUE) : '');
            $msg .= ' [message]' . $e->getMessage();

            log_error( 'data_source,sql,debug', $msg, self::TAG );

            _throw( new Charcoal_DBDataSourceException( $msg, $e ) );
        }

        return $result;
    }

    /**
     *  returns count of rows which are affected by previous SQL(DELETE/INSERT/UPDATE)
     *
     *  @return int         count of rows
     */
    function numRows()
    {
        return $this->num_rows;
    }

    /**
     *  fetch records as associative array
     *
     * @param PDOStatement $stmt
     *
     * @return array
     */
    public function fetchAssoc( $stmt )
    {
        return $stmt->fetch(PDO::FETCH_ASSOC);
//        return $stmt->fetch(PDO::FETCH_LAZY);
    }

    /**
     *  fetch records as arrays with numeric indexes
     *
     * @param PDOStatement $stmt
     *
     * @return array
     */
    public function fetchArray( $stmt )
    {
        return $stmt->fetch(PDO::FETCH_NUM);
    }

    /*
     *   最後に実行されたAUTO_INCREMENT値を取得
     */
    public function getLastInsertId()
    {
        $sql  = 'select LAST_INSERT_ID()';

        $result = $this->prepareExecute( $sql, NULL );

        if ( $row = $this->fetchArray($result) ){
            $val = $row[0];
            return $val;
        }

        return -1;
    }

    /**
     *   free result
     *
     * @param PDOStatement $stmt
     *
     * @return bool              TRUE if success, otherwise FALSE
     */
    public function free( $stmt )
    {
        return $stmt->closeCursor();
    }

    /**
     *   create recordset factory
     *
     * @param integer $fetch_mode    fetch mode(defined at Charcoal_IRecordset::FETCHMODE_XXX)
     * @param array $options         fetch mode options
     *
     * @return Charcoal_PDORecordsetFactory
     */
    public function createRecordsetFactory( $fetch_mode = NULL, $options = NULL )
    {
        return new Charcoal_PDORecordsetFactory( $fetch_mode, $options );
    }

}

