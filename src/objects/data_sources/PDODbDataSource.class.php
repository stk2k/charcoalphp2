<?php
/**
* PDOデータソースコンポーネント
*
* PHP version 5
*
* @package    data_sources
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_PDODbDataSource extends Charcoal_CharcoalObject implements Charcoal_IDataSource
{
	private $_connected = false;
	private $_connection;

	private $_backend;
	private $_user;
	private $_password;
	private $_db_name;
	private $_server;
	private $_charset;
	private $_autocommit;
	private $_command_id;

	private $_trans_cnt;

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_connected 	= false;
		$this->_connection 	= null;
		$this->_command_id 	= 0;
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
		$this->_backend   = $config->getString( s('backend'), s('') );
		$this->_user      = $config->getString( s('user'), s('') );
		$this->_password  = $config->getString( s('password'), s('') );
		$this->_db_name   = $config->getString( s('db_name'), s('') );
		$this->_server    = $config->getString( s('server'), s('') );
		$this->_charset   = $config->getString( s('charset'), s('') );
		$this->_autocommit = $config->getBoolean( s('autocommit'), b(FALSE) );
/*
		log_debug( "data_source", "data_source", "[PearDbDataSource]backend=" . $this->_backend );
		log_debug( "data_source", "data_source", "[PearDbDataSource]user=" . $this->_user );
		log_debug( "data_source", "data_source", "[PearDbDataSource]password=" . $this->_password );
		log_debug( "data_source", "data_source", "[PearDbDataSource]db_name=" . $this->_db_name );
		log_debug( "data_source", "data_source", "[PearDbDataSource]server=" . $this->_server );
		log_debug( "data_source", "data_source", "[PearDbDataSource]charset=" . $this->_charset );
		log_debug( "data_source", "data_source", "[PearDbDataSource]autocommit=" . $this->_autocommit );
*/
	}

	/*
	 *    接続済みか
	 */
	public function isConnected()
	{
		return $this->_connected;
	}

	/*
	 *    バックエンドを取得
	 */
	public function getBackend()
	{
		return $this->_backend;
	}

	/*
	 *    接続先サーバを取得
	 */
	public function getServer()
	{
		return $this->_server;
	}

	/*
	 *    接続ユーザ名を取得
	 */
	public function getUser()
	{
		return $this->_user;
	}

	/*
	 *    接続パスワードを取得
	 */
	public function getPassword()
	{
		return $this->_password;
	}

	/*
	 *    接続先データベース名を取得
	 */
	public function getDatabaseName()
	{
		return $this->_db_name;
	}

	/*
	 *    接続時の文字コードを取得
	 */
	public function getCharacterSet()
	{
		return $this->_charset;
	}

	/*
	 *    自動コミット機能をON/OFF
	 */
	public function autoCommit( Charcoal_Boolean $onoff = NULL )
	{
		if ( $onoff === NULL ){
			$onoff = b(TRUE);
		}

		try {
			// 接続処理
			$this->connect();

			$this->_connection->setAttribute( PDO::ATTR_AUTOCOMMIT, $onoff->isTrue() );
			
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

			$this->_connection->beginTransaction();

			$this->_trans_cnt ++;
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

			if ( $this->_trans_cnt > 0 )
			{
				$this->_connection->commit();

				$this->_trans_cnt --;
			}
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

			if ( $this->_trans_cnt > 0 )
			{
				$this->_connection->rollback();

				$this->_trans_cnt --;
			}
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBDataSourceException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *    デフォルトの接続先に接続
	 */
	public function connectDefault( $db_name_enabled = TRUE, $force = FALSE )
	{
		$db_name = $db_name_enabled ? $this->_db_name : NULL;
		$this->connect( $this->_backend, $this->_user, $this->_password, $this->_server, s($db_name), $force );
	}

	/*
	 *    接続
	 */
	public function connect( $force = FALSE )
	{
		// 接続済みなら何もしない
		if ( $this->_connected && !$force ){
			return;
		}

		$backend   = $this->_backend;
		$user      = $this->_user;
		$password  = $this->_password;
		$db_name   = $this->_db_name;
		$server    = $this->_server;
		$charset   = $this->_charset;

		try{
			$DSN = "$backend:host=$server; dbname=$db_name";

//			log_info( "debug,sql,data_source", "data_source", "connecting database: DSN=[$DSN]" );

			$driver_options = array();

			// 文字化け対策
/*
			$charset = $this->_charset;

			if ( !$charset->isEmpty() ){
				$charset = $charset->getValue();
				switch( strtolower($charset) ){
				case 'utf8':	$driver_options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET CHARACTER SET `utf8`";		break;
				case 'ujis':	$driver_options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET CHARACTER SET `ujis`";		break;
				case 'sjis':	$driver_options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET CHARACTER SET `sjis`";		break;
				default:
					_throw( new DataSourceConfigException( s('charset'), s('INVALID_CHARSET_VALUE: ' . $charset) ) );
				}
			}
*/

			$pdo = new PDO( $DSN, $user, $password, $driver_options );
//			log_info( "debug,sql,data_source", "data_source", "PDO object created. driver_options=" . print_r($driver_options,true) );

			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 			// enable server-side prepare statement

//			log_info( "debug,sql,data_source", "data_source", "connected database: DSN=[$DSN]" );

			$this->_connection = $pdo;
			$this->_connected = true;

			// 文字化け対策
			$charset = $this->_charset;
			if ( !$charset->isEmpty() ){
				$charset = $charset->getValue();
				switch( strtolower($charset) ){
				case 'utf8':	$this->_query( s('SET NAMES `utf8`') );		break;
				case 'ujis':	$this->_query( s('SET NAMES `ujis`') );		break;
				case 'sjis':	$this->_query( s('SET NAMES `sjis`') );		break;
				default:
					_throw( new DataSourceConfigException( s('charset'), s('INVALID_CHARSET_VALUE: ' . $charset) ) );
				}
		//		$this->_query( "set character set $charset" );
			}

			// 自動コミット
			$autocommit = $this->_autocommit;
			$this->_connection->setAttribute( PDO::ATTR_AUTOCOMMIT, $autocommit->isTrue() );
//			log_info( "debug,sql,data_source", "data_source", "autocommit: [$autocommit]" );

			$this->_trans_cnt = 0;
		}
		catch ( Exception $e )
		{
			_catch( $e );

			_throw( new Charcoal_DBConnectException( __METHOD__ . " failed: [db_string]$db_string", $e ) );
		}

	}

	/*
	 *    接続を閉じる
	 */
	public function disconnect()
	{

		// 接続していないなら何もしない
		if ( !$this->_connected ){
			return;
		}

		// 切断
		$this->_connection = NULL;

		$this->_connected = FALSE;
	}

	/*
	 *    プリペアドステートメントの発行
	 */
	private function _prepareExecute( Charcoal_String $sql, Charcoal_Vector $params = NULL )
	{
		Charcoal_Benchmark::start();

		$command_id = $this->_command_id++;

		$sql = $sql->getValue();
		$params_disp = $params ? $params->join(s(','),b(TRUE)) :'';

		log_info( "data_source,sql,debug", "data_source", "[ID]$command_id [SQL]$sql" );
		log_info( "data_source,sql,debug", "data_source", "[ID]$command_id [params]$params_disp" );
/*
		$log_sql = str_replace( '?', '%s', $sql );
		$log_params = NULL;
		if ( $params ){
			foreach( $params as $value ){
				if ( is_string($value) || $value instanceof Charcoal_String || $value instanceof Charcoal_Date || $value instanceof Charcoal_DateWithTime ){
//					$value = $this->_connection->quote($value);
					$log_params[] = "'{$value}'";
				}
				else{
					$log_params[] = $value;
				}
			}
		}
		$log_sql = $log_params ? vsprintf( $log_sql, uv($log_params) ) : $log_sql;
		log_info( "data_source,sql,debug", "data_source", "[ID]$command_id [SQL]$log_sql" );
*/

		$stmt = $this->_connection->prepare($sql);

		$params = $params ? $params->toArray() : array();

		$success = $stmt->execute($params);

		if ( !$success ){
			list( $sqlstate, $err_code, $err_msg ) = $stmt->errorInfo();
			$msg = "PDO#execute failed. [ID]$command_id [SQL]$sql [params]$params_disp [SQLSTATE]$sqlstate [ERR_CODE]$err_code [ERR_MSG]$err_msg";
			log_error( "data_source,sql,debug", "data_source", "...FAILED: $msg" );
			_throw( new Charcoal_DBDataSourceException( $msg ) );
		}
		
		$numRows = $stmt->rowCount();
		log_info( "data_source,sql,debug", "data_source", "[ID]$command_id ...success(numRows=$numRows)" );

		// ログ
		$elapse = Charcoal_Benchmark::stop();
		log_debug( 'data_source,sql,debug', "data_source", "[ID]$command_id prepareExecute() end. time=[$elapse]msec.");

		return $stmt;
	}

	/*
	 *    SQLをそのまま発行
	 */
	private function _query( Charcoal_String $sql )
	{
		$sql = $sql->getValue();

//		log_info( "sql", "data_source", $sql );

		$stmt = $this->_connection->query( $sql );

		return $stmt;
	}

	/*
	 *    SQLをそのまま発行（結果セットあり）
	 */
	public function query( Charcoal_String $sql )
	{
		// 接続処理
		$this->connect();

		// SQLを実行して結果セットを得る
		$stmt = $this->_query( $sql );

		// 結果セットを返却
		return $stmt;
	}

	/*
	 *    SQLをそのまま発行（結果セットなし）
	 */
	public function execute( Charcoal_String $sql )
	{
		// 接続処理
		$this->connect();

		// SQLを実行
		$this->_query( $sql );
	}

	/*
	 *    プリペアドステートメントの発行
	 */
	public function prepareExecute( Charcoal_String $sql, Charcoal_Vector $params = NULL )
	{
		$result = null;

		// 接続処理
		$this->connect();
		
		try {
			// statementの実行
			$result = $this->_prepareExecute( $sql, $params );
		}
		catch ( Exception $e )
		{
			_catch( $e );

			$msg  = 'PearDbDataSource#prepareExecute() failed:';
			$msg .= ' [SQL]' . $sql->getValue();
			$msg .= ' [params]' . ($params ? $params->join(s(','),b(TRUE)) : '');

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

}

