<?php
/**
* data source for PEAR:DB
*
* PHP version 5
*
* @package    objects.data_sources
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_PearDbDataSource extends Charcoal_AbstractDataSource
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

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_connected 	= false;
		$this->_connection 	= null;
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$this->_backend    = $config->getString( 'backend', '' );
		$this->_user       = $config->getString( 'user', '' );
		$this->_password   = $config->getString( 'password', '' );
		$this->_db_name    = $config->getString( 'db_name', '' );
		$this->_server     = $config->getString( 'server', '' );
		$this->_charset    = $config->getString( 'charset', '' );
		$this->_autocommit = $config->getBoolean( 'autocommit', FALSE );

		if ( $this->getSandbox()->isDebug() )
		{
			log_debug( "db", "[PearDbDataSource]backend=" . $this->_backend );
			log_debug( "db", "[PearDbDataSource]user=" . $this->_user );
			log_debug( "db", "[PearDbDataSource]password=" . $this->_password );
			log_debug( "db", "[PearDbDataSource]db_name=" . $this->_db_name );
			log_debug( "db", "[PearDbDataSource]server=" . $this->_server );
			log_debug( "db", "[PearDbDataSource]charset=" . $this->_charset );
			log_debug( "db", "[PearDbDataSource]autocommit=" . $this->_autocommit );
		}
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
	 *    バックエンドを設定
	 */
	protected function setBackend( Charcoal_String $backend )
	{
		$this->_backend = $backend;
	}

	/*
	 *    接続先サーバを取得
	 */
	public function getServer()
	{
		return $this->_server;
	}

	/*
	 *    バックエンドを設定
	 */
	protected function setServer( Charcoal_String $server )
	{
		$this->_server = $server;
	}

	/*
	 *    接続ユーザ名を取得
	 */
	public function getUser()
	{
		return $this->_user;
	}

	/*
	 *    接続ユーザ名を設定
	 */
	protected function setUser( Charcoal_String $user )
	{
		$this->_user = $user;
	}

	/*
	 *    接続パスワードを取得
	 */
	public function getPassword()
	{
		return $this->_password;
	}

	/*
	 *    接続パスワードを設定
	 */
	protected function setPassword( Charcoal_String $password )
	{
		$this->_password = $password;
	}

	/*
	 *    接続先データベース名を取得
	 */
	public function getDatabaseName()
	{
		return $this->_db_name;
	}

	/*
	 *    接続先データベース名を設定
	 */
	protected function setDatabaseName( Charcoal_String $db_name )
	{
		$this->_db_name = $db_name;
	}

	/*
	 *    接続時の文字コードを取得
	 */
	public function getCharacterSet()
	{
		return $this->_charset;
	}

	/*
	 *    接続時の文字コードを設定
	 */
	protected function setCharacterSet( Charcoal_String $charset )
	{
		$this->_charset = $charset;
	}

	/*
	 *    接続時のオートコミットを取得
	 */
	public function getAutoCommit()
	{
		return $this->_charset;
	}

	/*
	 *    接続時のオートコミットを設定
	 */
	protected function setAutoCommit( Charcoal_Boolean $autocommit )
	{
		$this->_autocommit = $autocommit;
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
		
			$this->_connection->autoCommit( $onoff->isTrue() );
			
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBDataSourceException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *   ランザクションを開始
	 *
	 *   PEAR:DBにbeginTransに相当する関数がないのでautoAommit(FALSE)で代用
	 */
	public function beginTrans()
	{
		try {
			// 接続処理
			$this->connect();

			$this->_connection->autoCommit( FALSE );
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
		try {
			// 接続処理
			$this->connect();

			$this->_connection->commit();
			
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
		try {
			// 接続処理
			$this->connect();

			$this->_connection->rollback();
			
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
		$flag = new Charcoal_ErrorReportingSwitcher(0,E_DEPRECATED);

		// 接続済みなら何もしない
		if ( $this->_connected && !$force ){
			return;
		}

		Charcoal_Benchmark::start();

		$backend   = $this->_backend;
		$user      = $this->_user;
		$password  = $this->_password;
		$db_name   = $this->_db_name;
		$server    = $this->_server;
		$charset   = $this->_charset;

		require_once( 'DB.php' );

		$DSN = array(
		    'phptype'  => $backend->getValue(),
		    'username' => $user->getValue(),
		    'password' => $password->getValue(),
		    'hostspec' => $server->getValue(),
		    'database' => $db_name->getValue(),
		);
		$DSN = v($DSN);

		$db_string = nl2br($DSN->toString());

		try{
			log_info( "debug,sql,data_source", "data_source", "connecting database: DSN=[$db_string]" );

			$db = DB::connect( $DSN->toArray() );
			
			if ( DB::isError($db) ){
				$msg = 'message=[' . DB::errorMessage($db) . "] DSN=[$db_string]";
				log_error( "debug,sql,data_source,error", "data_source", $msg );
				_throw( new Charcoal_DBException( $msg ) );
			}
		
			log_info( "debug,sql,data_source", "data_source", "connected database: DSN=[$db_string]" );

			$this->_connection = $db;
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
			$this->_connection->autoCommit( $autocommit->isTrue() );
			log_info( "debug,sql,data_source", "data_source", "autocommit: [$autocommit]" );
		}
		catch ( Exception $e )
		{
			_catch( $e );

			$msg  = __METHOD__ . " failed: [db_string]$db_string";

			_throw( new Charcoal_DBDataSourceException( $msg ) );
		}

		// ログ
		$elapse = Charcoal_Benchmark::stop();
		log_debug( "debug,sql,data_source", "data_source", "connect() end. time=[$elapse]sec.");
	}

	/*
	 *    接続を閉じる
	 */
	public function disconnect()
	{
		$flag = new Charcoal_ErrorReportingSwitcher(0,E_DEPRECATED);

		// 接続していないなら何もしない
		if ( !$this->_connected ){
			return;
		}

		// 切断
		$this->_connection->disconnect();

		$this->_connected = FALSE;
	}

	/*
	 *    パラメータをログ文字列コードに変換するコールバック
	 */
	private function logString( $param )
	{
		$db_code = Profile::getString( s('DB_CODE') );
		$log_code = Profile::getString( s('LOG_CODE') );

		return mb_convert_encoding( $param, $log_code, $db_code );
	}

	/*
	 *    プリペアドステートメントの発行
	 */
	private function _prepareExecute( Charcoal_String $sql, Charcoal_Vector $params = NULL )
	{
		Charcoal_Benchmark::start();

		$flag = new Charcoal_ErrorReportingSwitcher(0,E_DEPRECATED);

		$sql = $sql->getValue();

//print "SQL:$sql start<BR>";
		$conv = Charcoal_EncodingConverter::fromString( $this->getSandbox(), 'DB', 'PHP' );
		$log_params = $conv->convertArray( v($params) );
		$log_message = "[SQL]" . $sql . ($params ? " [params]" . implode(",",$log_params) :'');
		log_debug( "sql,debug", $log_message );

//print "SQL:$sql<BR>";
/*
		for( $i=0; $i<count($params); $i++ ){
			$p1 = $params[$i];
			if ( is_string($p1) ){
				$params[$i] = $p1;
			}
		}
*/

		$stmt = $this->_connection->prepare($sql);
	
		if ( DB::isError($stmt) ){
			$msg = $stmt->getMessage() . "(" . $stmt->getCode() . ")";
			log_error( "system", $msg );
			log_error( "sql,debug", "...FAILED: $msg" );
			_throw( new Charcoal_DBDataSourceException( $msg ) );
		}

		$params = $params ? $params->toArray() : array();

		$result = $this->_connection->execute($stmt, $params);
		
		if ( DB::isError($result) ){
			$msg = $result->getMessage();
			log_error( "system", $msg );
			log_error( "sql,debug", "...FAILED: $msg" );
			_throw( new Charcoal_DBDataSourceException( $msg ) );
		}

		if ( is_object($result) ){
			$numRows = $result->numRows();
			log_debug( "sql,debug", "...success(numRows=$numRows)" );
		}
		else{
			$numRows = null;
			log_debug( "sql,debug", "...success($result)" );
		}

		// ログ
		$elapse = Charcoal_Benchmark::stop();
		log_debug( 'sql,debug', "prepareExecute() end. time=[$elapse]sec.");

		return $result;
	}

	/*
	 *    SQLをそのまま発行
	 */
	private function _query( Charcoal_String $sql )
	{
		$flag = new Charcoal_ErrorReportingSwitcher(0,E_DEPRECATED);

		$sql = $sql->getValue();

		log_info( "sql", "[SQL]" . $sql );

		$result = $this->_connection->query( $sql );
			
		if ( DB::isError($result) ){
			$msg = $result->getMessage() . " [SQL]" . $sql;
			_throw( new Charcoal_DBDataSourceException( $msg ) );
		}

		return $result;
	}

	/*
	 *    SQLをそのまま発行（結果セットあり）
	 */
	public function query( Charcoal_String $sql )
	{
		$flag = new Charcoal_ErrorReportingSwitcher(0,E_DEPRECATED);

		// 接続処理
		$this->connect();

		Charcoal_Benchmark::start();

		// SQLを実行して結果セットを得る
		$resultset = $this->_query( $sql );

		// ログ
		$elapse = Charcoal_Benchmark::stop();
		log_debug( 'sql', "query() end. time=[$elapse]sec.");

		// 結果セットを返却
		return $resultset;
	}

	/*
	 *    SQLをそのまま発行（結果セットなし）
	 */
	public function execute( Charcoal_String $sql )
	{
		$flag = new Charcoal_ErrorReportingSwitcher(0,E_DEPRECATED);

		// 接続処理
		$this->connect();

		$start = Benchmark::nowTime();

		// SQLを実行
		$this->_query( $sql );

		// ログ
		$now = Benchmark::nowTime();
		$elapse = round( $now - $start, 4 );
		log_debug( 'sql', "execute() end. time=[$elapse]sec.");
	}

	/*
	 *    プリペアドステートメントの発行
	 */
	public function prepareExecute( Charcoal_String $sql, Charcoal_Vector $params = NULL )
	{
		$flag = new Charcoal_ErrorReportingSwitcher(0,E_DEPRECATED);

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

			_throw( new Charcoal_DBDataSourceException( __METHOD__ . " failed: [SQL]$sql [params]$params", $e ) );
		}

		return $result;
	}

	/*
	 *    実行結果件数取得
	 */
	function numRows( $result )
	{
		return $result->numRows();
	}

	/*
	 *    フェッチ処理（連想配列で返却）
	 */
	public function fetchAssoc( $result )
	{
		Charcoal_ParamTrait::checkResource( 1, $result );

		$flag = new Charcoal_ErrorReportingSwitcher(0,E_DEPRECATED);

		if ( !is_object($result) ){
			_throw( new Charcoal_NonObjectException( $result ) );
		}
		return $result->fetchRow(DB_FETCHMODE_ASSOC);
	}

	/*
	 *    フェッチ処理（配列で返却）
	 */
	public function fetchArray( $result )
	{
		$flag = new Charcoal_ErrorReportingSwitcher(0,E_DEPRECATED);

		if ( $result === NULL ){
			_throw( new NullPointerException() );
		}
		if ( !is_object($result) ){
			_throw( new NonObjectException( $result ) );
		}
		return $result->fetchRow(DB_FETCHMODE_ORDERED);
	}

	/*
	 *   最後に実行されたAUTO_INCREMENT値を取得
	 */
	public function getLastInsertId()
	{
		$flag = new Charcoal_ErrorReportingSwitcher(0,E_DEPRECATED);

		$sql  = 'select LAST_INSERT_ID()';

		$result = $this->prepareExecute( $sql, NULL );

		if ( $row = $this->fetchArray($result) ){
			$val = $row[0];
			return $val;
		}

		return -1;
	}

}

