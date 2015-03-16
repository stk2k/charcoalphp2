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
	private $connection;

	private $backend;
	private $user;
	private $password;
	private $db_name;
	private $server;
	private $port;
	private $charset;
	private $autocommit;
	private $command_id;

	private $trans_cnt;

	private $exec_sql_stack;

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
			log_debug( 'data_source', "backend=" . $this->backend, 'data_source' );
			log_debug( 'data_source', "user=" . $this->user, 'data_source' );
			log_debug( 'data_source', "password=" . $this->password, 'data_source' );
			log_debug( 'data_source', "db_name=" . $this->db_name, 'data_source' );
			log_debug( 'data_source', "server=" . $this->server, 'data_source' );
			log_debug( 'data_source', "port=" . $this->port, 'data_source' );
			log_debug( 'data_source', "charset=" . $this->charset, 'data_source' );
			log_debug( 'data_source', "autocommit=" . $this->autocommit, 'data_source' );
			log_debug( 'data_source', "set_names=" . $this->set_names, 'data_source' );
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
			Charcoal_ParamTrait::checkBoolean( 1, $on );

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
				$charset_db = isset($db_charset_map[$this->charset]) ? $db_charset_map[$this->charset] : NULL;
			}
			$port = !empty($port) ? "port={$port};" : '';
			$charset = $charset_db ? "charset={$charset_db};" : '';
			$DSN = "$backend:host=$server;{$port}dbname=$db_name;{$charset}";

			log_info( 'debug, sql, data_source', "DSN=[$DSN]", 'data_source' );

			$options = array(
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_EMULATE_PREPARES => false,
					PDO::ATTR_AUTOCOMMIT => $this->autocommit,
				);
			log_info( 'debug, sql, data_source', 'driver options:' . print_r($options, true), 'data_source' );

			$pdo = new PDO( $DSN, $user, $password, $options );

			$this->connection = $pdo;
			$this->connected = true;

			log_info( 'debug, sql, data_source', "connected database: DSN=[$DSN]", 'data_source' );

			if ( $this->set_names ){
				switch( strtolower($this->charset) ){
					case 'utf8':	$this->_query( s('SET NAMES `utf8`') );		break;
					case 'ujis':	$this->_query( s('SET NAMES `ujis`') );		break;
					case 'sjis':	$this->_query( s('SET NAMES `sjis`') );		break;
					default:
						_throw( new DataSourceConfigException( s('charset'), s('INVALIDcharset_VALUE: ' . $charset) ) );
				}
			}
		}
		catch ( Exception $e )
		{
			_catch( $e );

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

	/*
	 *    プリペアドステートメントの発行
	 */
	private function _prepareExecute( $sql, $params = NULL )
	{
		Charcoal_ParamTrait::checkString( 1, $sql );
		Charcoal_ParamTrait::checkVector( 2, $params, TRUE );

		$params = uv( $params );

		$timer_handle = Charcoal_Benchmark::start();

		$command_id = $this->command_id++;

		$params_disp = $params ? implode( ',' , $params ) :'';

		log_info( "data_source,sql,debug", "[ID]$command_id [SQL]$sql", 'data_source' );
		log_info( "data_source,sql,debug", "[ID]$command_id [params]$params_disp", 'data_source' );
/*
		$log_sql = str_replace( '?', '%s', $sql );
		$log_params = NULL;
		if ( $params ){
			foreach( $params as $value ){
				if ( is_string($value) || $value instanceof Charcoal_String || $value instanceof Charcoal_Date || $value instanceof Charcoal_DateWithTime ){
//					$value = $this->connection->quote($value);
					$log_params[] = "'{$value}'";
				}
				else{
					$log_params[] = $value;
				}
			}
		}
		$log_sql = $log_params ? vsprintf( $log_sql, uv($log_params) ) : $log_sql;
		log_info( "data_source,sql,debug", 'data_source', "[ID]$command_id [SQL]$log_sql" );
*/

		$stmt = $this->connection->prepare( $sql );

		$params = $params ? $params : array();

		$this->exec_sql_stack->push( new Charcoal_ExecutedSQL($sql, $params) );
		

		$success = $stmt->execute( $params );

		if ( !$success ){
			list( $sqlstate, $err_code, $err_msg ) = $stmt->errorInfo();
			$msg = "PDO#execute failed. [ID]$command_id [SQL]$sql [params]$params_disp [SQLSTATE]$sqlstate [ERR_CODE]$err_code [ERR_MSG]$err_msg";
			log_error( "data_source,sql,debug", "...FAILED: $msg", 'data_source' );
			_throw( new Charcoal_DBDataSourceException( $msg ) );
		}

		$numRows = $stmt->rowCount();
		log_info( "data_source,sql,debug", "[ID]$command_id ...success(numRows=$numRows)", 'data_source' );

		// ログ
		$elapse = Charcoal_Benchmark::stop( $timer_handle );
		log_debug( 'data_source,sql,debug', "[ID]$command_id prepareExecute() end. time=[$elapse]msec.", 'data_source' );

		return $stmt;
	}

	/*
	 *    SQLをそのまま発行
	 */
	private function _query( $sql )
	{
		Charcoal_ParamTrait::checkString( 1, $sql );

		$sql = us( $sql );

		$command_id = $this->command_id++;

		log_info( "data_source,sql,debug", "[ID]$command_id [SQL]$sql", 'data_source' );

		$this->exec_sql_stack->push( new Charcoal_ExecutedSQL($sql) );

		$stmt = $this->connection->query( $sql );

		return $stmt;
	}

	/*
	 *    SQLをそのまま発行（結果セットあり）
	 */
	public function query( $sql )
	{
		Charcoal_ParamTrait::checkString( 1, $sql );

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
		Charcoal_ParamTrait::checkString( 1, $sql );

		// 接続処理
		$this->connect();

		$this->exec_sql_stack->push( new Charcoal_ExecutedSQL($sql) );

		// SQLを実行
		$this->_query( $sql );
	}

	/*
	 *    プリペアドステートメントの発行
	 */
	public function prepareExecute( $sql, $params = NULL )
	{
		try {
			Charcoal_ParamTrait::checkString( 1, $sql );
			Charcoal_ParamTrait::checkVector( 2, $params, TRUE );

			$result = null;

			// 接続処理
			$this->connect();
			
			// statementの実行
			$result = $this->_prepareExecute( $sql, $params );
		}
		catch ( Exception $e )
		{
			_catch( $e );

			$msg  = __METHOD__ . ' failed:';
			$msg .= ' [SQL]' . $sql;
			$msg .= ' [params]' . ($params ? v($params)->join(',',TRUE) : '');

			_throw( new Charcoal_DBDataSourceException( $msg, $e ) );
		}

		return $result;
	}

	/*
	 *    実行結果件数取得
	 */
	function numRows( $stmt )
	{
		return $stmt->rowCount();
	}

	/*
	 *    create recordset object
	 */
	public function createRecordset( $result )
	{
		
	}

	/*
	 *    フェッチ処理（連想配列で返却）
	 */
	public function fetchAssoc( $stmt )
	{
		return $stmt->fetch(PDO::FETCH_ASSOC);
//		return $stmt->fetch(PDO::FETCH_LAZY);
	}

	/*
	 *    フェッチ処理（配列で返却）
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

	/*
	 *   create recordset factory
	 */
	public function createRecordsetFactory()
	{
		return new Charcoal_PDORecordsetFactory();
	}

}

