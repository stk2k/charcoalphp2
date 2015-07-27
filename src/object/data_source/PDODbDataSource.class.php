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
	private $connected = false;

	/** @var PDO */
	private $connection;

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

	private $exec_sql_stack;

	private $num_rows;

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();

		$this->connected 	= false;
		$this->connection 	= null;
		$this->command_id 	= 0;

		$this->exec_sql_stack = new Charcoal_Stack();
	}

	/**
	 * Get configurations
	 *
	 * @return array   configuration data
	 */
	public function getConfig()
	{
		return array(
				'backend' => $this->backend,
				'user' => $this->user,
				'password' => $this->password,
				'db_name' => $this->db_name,
				'server' => $this->server,
				'port' => $this->port,
				'charset' => $this->charset,
				'autocommit' => $this->autocommit,
				'set_names' => $this->set_names,
				'buffered_query' => $this->buffered_query,
			);
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$this->backend   = us( $config->getString( 'backend' ) );
		$this->user      = us( $config->getString( 'user' ) );
		$this->password  = us( $config->getString( 'password' ) );
		$this->db_name   = us( $config->getString( 'db_name' ) );
		$this->server    = us( $config->getString( 'server' ) );
		$this->port      = ui( $config->getInteger( 'port' ) );
		$this->charset   = us( $config->getString( 'charset' ) );
		$this->autocommit = ub( $config->getBoolean( 'autocommit', TRUE ) );
		$this->set_names  = ub( $config->getBoolean( 'set_names', FALSE ) );
		$this->buffered_query  = ub( $config->getBoolean( 'buffered_query', TRUE ) );

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
			log_debug( 'data_source', "backend=" . $this->backend, __METHOD__ );
			log_debug( 'data_source', "user=" . $this->user, __METHOD__ );
			log_debug( 'data_source', "password=" . $this->password, __METHOD__ );
			log_debug( 'data_source', "db_name=" . $this->db_name, __METHOD__ );
			log_debug( 'data_source', "server=" . $this->server, __METHOD__ );
			log_debug( 'data_source', "port=" . $this->port, __METHOD__ );
			log_debug( 'data_source', "charset=" . $this->charset, __METHOD__ );
			log_debug( 'data_source', "autocommit=" . $this->autocommit, __METHOD__ );
			log_debug( 'data_source', "set_names=" . $this->set_names, __METHOD__ );
			log_debug( 'data_source', "buffered_query=" . $this->buffered_query, __METHOD__ );
		}
	}

	/**
	 *	get last exectuted SQL
	 *	
	 *	@param bool|Charcoal_Boolean $throw   If TRUE, throws Charcoal_StackEmptyException when executed SQL stack is empty.
	 *	
	 *	@return Charcoal_ExecutedSQL       executed SQL
	 */
	public function popExecutedSQL( $throw = FALSE )
	{
		$throw = b($throw);

		$item = NULL;
		try{
			$item = $this->exec_sql_stack->pop();
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
	 *	get all exectuted SQLs
	 *	
	 *	@return array       executed SQLs
	 */
	public function getAllExecutedSQL()
	{
		return $this->exec_sql_stack->getAll();
	}

	/*
	 *    接続済みか
	 */
	public function isConnected()
	{
		return $this->connected;
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
	 */
	public function autoCommit( $on )
	{
		try {
			Charcoal_ParamTrait::validateBoolean( 1, $on );

			// 接続処理
			$this->connect();

			$this->connection->setAttribute( PDO::ATTR_AUTOCOMMIT, $on );
			
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
//		log_debug( "transaction,sql", "commitTrans", "beginTrans called from:" . print_r( Charcoal_System::caller(1), true ) );
		try {
			// 接続処理
			$this->connect();

			$this->connection->beginTransaction();
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
//		log_debug( "transaction,sql", "commitTrans", "rollbackTrans called from:" . print_r( Charcoal_System::caller(1), true ) );
		try {
			// 接続処理
			$this->connect();

			$this->connection->commit();
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
//		log_debug( "transaction,sql", "transaction", "rollbackTrans called from:" . print_r( Charcoal_System::caller(1), true ) );
		try {
			// 接続処理
			$this->connect();

			$this->connection->rollback();
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBDataSourceException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *    接続
	 */
	public function connect( $force = FALSE )
	{
		$DSN = NULL;

		// 接続済みなら何もしない
		if ( $this->connected && !$force ){
			return;
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

			log_info( 'debug, sql, data_source', "DSN=[$DSN]", __METHOD__ );

			$options = array(
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_EMULATE_PREPARES => false,
					PDO::ATTR_AUTOCOMMIT => $this->autocommit,
					PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => $this->buffered_query,
				);
			log_info( 'debug, sql, data_source', 'driver options:' . print_r($options, true), __METHOD__ );

			$th_connect = Charcoal_Benchmark::start();

			$pdo = new PDO( $DSN, $user, $password, $options );

			$bench_score = Charcoal_Benchmark::stop( $th_connect );
			log_debug( 'data_source,sql,debug', sprintf("connected in [%0.4f] msec",$bench_score), __METHOD__ );

			$this->connection = $pdo;
			$this->connected = true;

			log_info( 'debug, sql, data_source', "connected database: DSN=[$DSN]", __METHOD__ );

			if ( $this->set_names ){
				switch( strtolower($this->charset) ){
					case 'utf8':	$this->_query( s('SET NAMES `utf8`') );		break;
					case 'ujis':	$this->_query( s('SET NAMES `ujis`') );		break;
					case 'sjis':	$this->_query( s('SET NAMES `sjis`') );		break;
					default:
						_throw( new Charcoal_DataSourceConfigException( 'charset', "invalid charset: $charset" ) );
				}
			}
		}
		catch ( Exception $e )
		{
			_catch( $e );

			log_error( 'data_source,sql,debug', __METHOD__ . " failed: DSN=[$DSN]", __METHOD__ );

			_throw( new Charcoal_DBConnectException( __METHOD__ . " failed: DSN=[$DSN]", $e ) );
		}

	}

	/*
	 *    接続を閉じる
	 */
	public function disconnect()
	{

		// 接続していないなら何もしない
		if ( !$this->connected ){
			return;
		}

		// 切断
		$this->connection = NULL;

		$this->connected = FALSE;
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

		log_debug( 'data_source,sql,debug', "[ID]$command_id [SQL]$sql", __METHOD__ );
		log_debug( 'data_source,sql,debug', "[ID]$command_id [params]$params_disp", __METHOD__ );

		/** @var PDOStatement $stmt */
		$stmt = $this->connection->prepare( $sql, $driver_options );

		$this->exec_sql_stack->push( new Charcoal_ExecutedSQL($sql, $params) );

		$success = $stmt->execute( $params );

		if ( !$success ){
			list( $sqlstate, $err_code, $err_msg ) = $stmt->errorInfo();
			$msg = "PDO#execute failed. [ID]$command_id [SQL]$sql [params]$params_disp [SQLSTATE]$sqlstate [ERR_CODE]$err_code [ERR_MSG]$err_msg";
			log_error( 'data_source,sql,debug', "...FAILED: $msg", __METHOD__ );
			_throw( new Charcoal_DBDataSourceException( $msg ) );
		}

		$this->num_rows = $stmt->rowCount();
		log_info( 'data_source,sql,debug', "[ID]$command_id ...success(numRows={$this->num_rows})", __METHOD__ );

		// ログ
		$elapse = Charcoal_Benchmark::stop( $timer_handle );
		log_debug( 'data_source,sql,debug', "[ID]$command_id _prepareExecute() end. time=[$elapse]msec.", __METHOD__ );

		return $stmt;
	}

	/*
	 *    SQLをそのまま発行
	 */
	private function _query( $sql )
	{
		Charcoal_ParamTrait::validateString( 1, $sql );

		$sql = us( $sql );

		$command_id = $this->command_id++;

		log_info( 'data_source,sql,debug', "[ID]$command_id [SQL]$sql", __METHOD__ );

		$this->exec_sql_stack->push( new Charcoal_ExecutedSQL($sql) );

		$stmt = $this->connection->query( $sql );

		return $stmt;
	}

	/*
	 *    SQLをそのまま発行（結果セットあり）
	 */
	public function query( $sql )
	{
		Charcoal_ParamTrait::validateString( 1, $sql );

		// 接続処理
		$this->connect();

		$this->exec_sql_stack->push( new Charcoal_ExecutedSQL($sql) );

		// SQLを実行して結果セットを得る
		$stmt = $this->_query( $sql );

		// 結果セットを返却
		return $stmt;
	}

	/*
	 *    SQLをそのまま発行（結果セットなし）
	 */
	public function execute( $sql )
	{
		Charcoal_ParamTrait::validateString( 1, $sql );

		// 接続処理
		$this->connect();

		$this->exec_sql_stack->push( new Charcoal_ExecutedSQL($sql) );

		// SQLを実行
		$this->_query( $sql );
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

			log_error( 'data_source,sql,debug', $msg, __METHOD__ );

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
//		return $stmt->fetch(PDO::FETCH_LAZY);
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

