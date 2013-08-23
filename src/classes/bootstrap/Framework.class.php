<?php
/**
 * CharcoalPHP ver 2.9.6
 * E-Mail for multibyte charset
 *
 * PHP versions 5 and 6 (PHP5.2 upper)
 *
 * Copyright 2013, stk2k in japan
 * Technical  :  http://charcoalphp.org/
 * Licensed under The MIT License License
 *
 * @copyright		Copyright 2013, stk2k.
 * @link			http://charcoalphp.org/
 * @version			2.9.6
 * @lastmodified	2013-04-26
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 * 
 * CharcoalPHP is a task-oriented web framework.
 * 
 * Copyright (C) 2013   stk2k 
 */

/*
 *	Framework global error handler
 */
function charcoal_global_error_handler( $errno, $errstr, $errfile, $errline )
{ 
	if ( $errno == E_ERROR || $errno == E_PARSE || $errno == E_RECOVERABLE_ERROR || $errno == E_USER_ERROR )
	{
		// create fake exception
		$e = new Charcoal_PHPErrorException($errno, $errstr, $errfile, $errline);

		Charcoal_FrameworkExceptionStack::push( $e );

		exit;	// prevent unnecessary errors to add
	}
/*
$errno = Charcoal_System::phpErrorString( $errno );
echo "[errno]$errno [errstr]$errstr [errfile]$errfile [errline]$errline" . eol();
*/
	return TRUE;	// Otherwise, ignore all errors
}

/*
 *	Framework global exception handler
 */
function charcoal_global_exception_handler( $exception )
{ 
//	log_fatal( "system,error", "charcoal_global_exception_handler: $exception" );

	// 例外ハンドラに処理を委譲
	Charcoal_ExceptionHandlerList::handleFrameworkException( $exception );
}

/*
 *	Framework global shutdown handler
 */
function charcoal_shutdown_handler()
{
	if ( Charcoal_Framework::testEchoFlag( i(Charcoal_EnumEchoFlag::ECHO_SHUTDOWN_HOOK) ) ){
		echo "[shutdown_handler] Shutdown handler start" . eol();
	}
//	log_info( "system", "shutdown", 'Shutdown handler start' );

	if ( $error = error_get_last() )
	{
		if ( Charcoal_Framework::testEchoFlag( i(Charcoal_EnumEchoFlag::ECHO_SHUTDOWN_HOOK) ) ){
			echo "[shutdown_handler] error_get_last:" . print_r($error_get_last,true) . eol();
		}

		switch( $error['type'] )
		{
			case E_ERROR:
			case E_PARSE:
			case E_CORE_ERROR:
			case E_CORE_WARNING:
			case E_COMPILE_ERROR:
			case E_COMPILE_WARNING:
			case E_USER_ERROR:
				$e = new Charcoal_PHPErrorException($error['type'], $error['message'], $error['file'], $error['line']);
				Charcoal_FrameworkExceptionStack::push( $e );
				break;
		}
	}

	if ( Charcoal_Framework::testEchoFlag( i(Charcoal_EnumEchoFlag::ECHO_SHUTDOWN_HOOK) ) ){
		echo "[shutdown_handler] handling exceptions." . eol();
	}

	while( $e = Charcoal_FrameworkExceptionStack::pop() )
	{
		if ( Charcoal_Framework::testEchoFlag( i(Charcoal_EnumEchoFlag::ECHO_SHUTDOWN_HOOK) ) ){
			echo "[shutdown_handler] FrameworkExceptionStack::pop:" . get_class($e) . eol();
		}

		if ( $e instanceof Charcoal_CharcoalException ){
			// Delegate framework exception handling to handlers
			$handled = Charcoal_ExceptionHandlerList::handleFrameworkException( $e );
			$handled = b($handled);
			if ( $handled->isFalse() )
			{
				// Forgot to handle exception?
				print "Framweork Exception Not Handled:" . eol() . $e . eol();
			}
		}
		else if ( $e instanceof Exception ){
			// Delegate framework exception handling to handlers
			$handled = Charcoal_ExceptionHandlerList::handleException( $e );
			$handled = b($handled);
			if ( $handled->isFalse() )
			{
				// Forgot to handle exception?
				print "Exception Not Handled:" . eol() . $e . eol();
			}
		}
	}

	if ( Charcoal_Framework::testEchoFlag( i(Charcoal_EnumEchoFlag::ECHO_SHUTDOWN_HOOK) ) ){
		echo "[shutdown_handler] handled exceptions." . eol();
	}

//	log_info( "system", "shutdown", 'Shutdown handler end' );

	// Process core hook messages
	Charcoal_CoreHook::processAll();

	$end_time = Charcoal_Benchmark::nowTime();
	$elapse = round( $end_time - Charcoal_Framework::getStartTime(), 4 );
//	log_debug( "system,debug", 'framework',"total framework process time: [$elapse] msec" );

	// Terminate log messages
	Charcoal_Logger::terminate();

	if ( Charcoal_Framework::testEchoFlag( i(Charcoal_EnumEchoFlag::ECHO_SHUTDOWN_HOOK) ) ){
		echo "[shutdown_handler] Shutdown handler end" . eol();
	}
}

/**
*  フレームワークのメインクラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_Framework
{
	const VERSION_MAJOR     = CHARCOAPHP_VERSION_MAJOR;
	const VERSION_MINOR     = CHARCOAPHP_VERSION_MINOR;
	const VERSION_REVISION  = CHARCOAPHP_VERSION_REVISION;
	const VERSION_BUILD     = CHARCOAPHP_VERSION_BUILD;

	const VERSION_PART_ALL       = 0xFFFF;
	const VERSION_PART_MAJOR     = 0x0001;
	const VERSION_PART_MINOR     = 0x0002;
	const VERSION_PART_REVISION  = 0x0004;
	const VERSION_PART_BUILD     = 0x0008;

	const VERSION_STRING_SEPERATOR = '.';

	static $proc_stack;
	static $request;
	static $debug_mode;
	static $print_debugtrace;
	static $hook_stage;
	static $echo_flag;
	static $loaded_files;
	static $start_time;

	/**
	 *	get start time
	 */
	public static function getStartTime()
	{
		return self::$start_time;
	}

	/**
	 *	load source file
	 */
	public static function loadSourceFile( Charcoal_File $file )
	{
		if ( !$file->exists() ){
			_throw( new Charcoal_FileNotFoundException($file) );
		}
		require_once( $file->getAbsolutePath() );
		self::$loaded_files[] = $file;
//		log_info( "system,debug,loaded_files", 'framework',"loaded source file: [$file]" );
	}

	/**
	 *	get loaded source file
	 */
	public static function getLoadedSourceFiles()
	{
		return self::$loaded_files;
	}

	/**
	 *	get echo flags
	 */
	public static function getEchoFlag()
	{
		return self::$echo_flag;
	}

	/**
	 *	set echo flags
	 */
	public static function setEchoFlag( Charcoal_Integer $echo_flag )
	{
		self::$echo_flag = ui($echo_flag);
	}

	/**
	 *	test echo flags
	 */
	public static function testEchoFlag( Charcoal_Integer $echo_flag )
	{
		$echo_flag = ui($echo_flag);
		$framework = self::$echo_flag;
		$ret = ($framework & $echo_flag) === $echo_flag;
		return $ret;
	}

	/**
	 *	プロシージャスタックを取得
	 */
	public static function getProcedureStack()
	{
		return self::$proc_stack;
	}

	/*
	 *	プロシージャをスタックに追加
	 */
	public static function pushProcedure( Charcoal_IProcedure $procedure )
	{
		if ( !self::$proc_stack ){
			self::$proc_stack = new Charcoal_Stack();
		}
		self::$proc_stack->push( $procedure );

//		log_debug( 'system, debug', "pushed procedure[" . $procedure->getObjectPath() . "]." );
	}

	/*
	 *	プロシージャをスタックから取得
	 */
	public static function popProcedure()
	{
		if ( !self::$proc_stack ){
			return NULL;
		}
		if ( self::$proc_stack->isEmpty() ){
			return NULL;
		}
		return self::$proc_stack->pop();
	}

	/*
	 *	Set hook stage and callback
	 */
	public static function callbackHook( Charcoal_Integer $hook_stage, Charcoal_Object $data = NULL )
	{
		self::$hook_stage = ui($hook_stage);

		if ( $data ){
			Charcoal_CoreHook::pushMessage( new Charcoal_CoreHookMessage($hook_stage, $data) );
		}
		else{
			Charcoal_CoreHook::pushMessage( new Charcoal_CoreHookMessage($hook_stage) );
		}
	}

	/*
	 *	Get hook stage
	 */
	public static function getHookStage()
	{
		return self::$hook_stage;
	}

	/*
	 *	HTTPエラードキュメントを表示
	 */
	public static function showHttpErrorDocument( Charcoal_Integer $status_code )
	{
		$status_code = ui($status_code);

		// HTML
		$html_file = $status_code . '.html';

		// アプリケーション以下のerror_docsを検索
		$html_file_path = Charcoal_ResourceLocator::getApplicationPath( s('error_docs') , s($html_file) );
		if ( !is_file($html_file_path) ){
//			log_info( 'system,debug,error', 'framework',"エラードキュメント($html_file_path)は存在しません。");

			// プロジェクト以下のerror_docsを検索
			$html_file_path = Charcoal_ResourceLocator::getProjectPath( s('error_docs') , s($html_file) );
			if ( !is_file($html_file_path) ){
//				log_debug( 'system,debug,error', 'framework',"エラードキュメント($html_file_path)は存在しません。");

				// フレームワーク以下のerror_docsを検索
				$html_file_path = Charcoal_ResourceLocator::getFrameworkPath( s('error_docs') , s($html_file) );
				if ( !is_file($html_file_path) ){
//					log_warning( 'system,debug,error', 'framework',"エラードキュメント($html_file_path)は存在しません。");
				}
			}
		}

		// 読み込みと表示
		if ( is_file($html_file_path) ){
			readfile( $html_file_path );
			print "<br>";
		}

		// ヘッダ文字列
		$status_message_file = Charcoal_ResourceLocator::getFrameworkPath( s('preset') , s('status_messages.ini') );
		if ( !is_file($status_message_file) ){
//			log_warning( 'system,debug,error', 'framework',"ステータスメッセージファイル($status_message_file)が存在しません。");
		}
		$status_messages = parse_ini_file($status_message_file,FALSE);
		if ( FALSE === $status_messages ){
//			log_warning( 'system,debug,error', 'framework',"ステータスメッセージファイル($status_message_file)の読み込みに失敗しました。");
		}
		$header_msg = isset($status_messages[$status_code]) ? $status_messages[$status_code] : '';

		// ヘッダ出力
		header( s("HTTP/1.0 $status_code $header_msg", true, $status_code) );

//		log_error( 'system,error', 'framework',"HTTP/1.0 $status_code $header_msg");
	}

	/*
	 *	バージョン番号を取得
	 */
	public static function getVersion( Charcoal_Integer $version_part = NULL )
	{
		$version_part = $version_part ? ui($version_part) : self::VERSION_PART_ALL;

		// パートが個別指定された場合は整数で返す
		$version = NULL;
		switch( $version_part ){
			case self::VERSION_PART_MAJOR:		$version = self::VERSION_MAJOR;		break;
			case self::VERSION_PART_MINOR:		$version = self::VERSION_MINOR;		break;
			case self::VERSION_PART_REVISION:	$version = self::VERSION_REVISION;		break;
			case self::VERSION_PART_BUILD:		$version = self::VERSION_BUILD;		break;
		}
		if ( $version !== NULL ){
			return $version;
		}

		// パートが複数指定された場合は文字列で返す
		$version_string = '';

		if ( $version_part & self::VERSION_PART_MAJOR ){
			$version_string .= self::VERSION_MAJOR;

			if ( $version_part & self::VERSION_PART_MINOR ){
				$version_string .= self::VERSION_STRING_SEPERATOR . self::VERSION_MINOR;

				if ( $version_part & self::VERSION_PART_REVISION ){
					$version_string .= self::VERSION_STRING_SEPERATOR . self::VERSION_REVISION;

					if ( $version_part & self::VERSION_PART_BUILD ){
						$version_string .= self::VERSION_STRING_SEPERATOR . self::VERSION_BUILD;
					}
				}
			}
		}

		return $version_string;
	}

	/*
	 *	デバッグモードか
	 */
	public static function isDebugMode()
	{
		return self::$debug_mode;
	}

	/*
	 *	スタックトレースの出力状態を取得
	 */
	public static function isPrintDebugTrace()
	{
		return self::$print_debugtrace;
	}

	/*
	 *	スタックトレースの出力状態を設定
	 */
	public static function setPrintDebugTrace( Charcoal_Boolean $print_debugtrace )
	{
		self::$print_debugtrace = ub($print_debugtrace);
	}

	/*
	 *	フレームワークのメジャーバージョン番号を取得
	 */
	public static function getMajorVersion()
	{
		return self::getVersion( i(self::VERSION_PART_MAJOR) );
	}

	/*
	 *	フレームワークのマイナーバージョン番号を取得
	 */
	public static function getMinorVersion()
	{
		return self::getVersion( i(self::VERSION_PART_MINOR) );
	}

	/*
	 *	フレームワークのリビジョン番号を取得
	 */
	public static function getRevision()
	{
		return self::getVersion( i(self::VERSION_PART_REVISION) );
	}

	/*
	 *	フレームワークのビルド番号を取得
	 */
	public static function getBuildNumber()
	{
		return self::getVersion( i(self::VERSION_PART_BUILD) );
	}

	/*
	 *	リクエストを取得
	 */
	public static function getRequest()
	{
		return self::$request;
	}

	/*
	 *	リクエストパスを取得
	 */
	public static function getRequestPath()
	{
		return self::$request ? self::$request->getProcedurePath() : NULL;
	}

	/*
	 *	リクエストIDを取得
	 */
	public static function getRequestID()
	{
		return self::$request ? self::$request->getRequestID() : NULL;
	}

	/*
	 *	フレームワークの実行（実体）
	 */
	private static function _run()
	{
		//=======================================
		// シャットダウンハンドラの登録
		//
		register_shutdown_function( "charcoal_shutdown_handler" );

		//=======================================
		// エラーハンドラの登録
		//
		set_error_handler( "charcoal_global_error_handler" );
		set_exception_handler( "charcoal_global_exception_handler" );

		self::$print_debugtrace = TRUE;
		self::$debug_mode = TRUE;

		//==================================================================
		// プロファイルを読み込む
		Charcoal_Profile::load( 'default' );
		Charcoal_Profile::load( CHARCOAL_PROFILE );

		//=======================================
		// ロガー初期化処理
		//
		Charcoal_Logger::init();

		//=======================================
		// output fronend PHP file
		//
		list($front_php_file, $front_php_line) = Charcoal_System::caller(1);
//		log_debug( "debug,system", 'framework',"Charcoal_Framework#run() called by: $front_php_file($front_php_line)" );

		//=======================================
		// Start bootstrap

		self::callbackHook( i(Charcoal_EnumCoreHookStage::START_OF_BOOTSTRAP) );

		//=======================================
		// フレームワーク初期化処理
		//

		self::callbackHook( i(Charcoal_EnumCoreHookStage::BEFORE_INIT_FRAMEWORK) );

		// タイムアウトを指定
		if ( !ini_get('safe_mode') ){
			$timeout = Charcoal_Profile::getInteger( s('SCRIPT_TIMEOUT'), i(600) )->getValue();
			set_time_limit( $timeout );
		}

		self::callbackHook( i(Charcoal_EnumCoreHookStage::AFTER_INIT_FRAMEWORK) );

		//=======================================
		// デバッグモード
		//

		self::$debug_mode = ub( Charcoal_Profile::getBoolean( s('DEBUG_MODE') ) );
//		log_debug( "debug,system", 'framework',"debug_mode=" . self::$debug_mode );

		//=======================================
		// クラスローダの登録
		//

		self::callbackHook( i(Charcoal_EnumCoreHookStage::BEFORE_REG_CLASS_LOADERS) );

		$framework_class_loader = Charcoal_Factory::createClassLoader( s('framework') );
			self::callbackHook( i(Charcoal_EnumCoreHookStage::CREATE_FRAMEWORK_CLASS_LOADER), s($loader_name) );

		Charcoal_ClassLoader::addClassLoader( $framework_class_loader );
			self::callbackHook( i(Charcoal_EnumCoreHookStage::REG_FRAMEWORK_CLASS_LOADER), s($loader_name) );

		$class_loaders = Charcoal_Profile::getArray( s('CLASS_LOADERS') );
		foreach( $class_loaders as $loader_name ) 	{
			$loader = Charcoal_Factory::createClassLoader( s($loader_name) );
			self::callbackHook( i(Charcoal_EnumCoreHookStage::CREATE_CLASS_LOADER), s($loader_name) );
			Charcoal_ClassLoader::addClassLoader( $loader );
			self::callbackHook( i(Charcoal_EnumCoreHookStage::REG_CLASS_LOADER), s($loader_name) );
		}

		self::callbackHook( i(Charcoal_EnumCoreHookStage::AFTER_REG_CLASS_LOADERS) );

		//=======================================
		// Requestパラメータの取得
		//

		// Requestオブジェクトを作成
		$request = Charcoal_Factory::createObject( s(CHARCOAL_RUNMODE), s('request'), s('Charcoal_IRequest') );
		self::$request = $request;
//		log_debug( "debug,system", 'framework',"request object created: " . print_r($request,true) );

		$proc_path = self::getRequestPath();
//		log_debug( "debug,system", 'framework',"proc_path=" . $proc_path );

		//=======================================
		// Register core hook objects

		$hooks = Charcoal_Profile::getArray( s('CORE_HOOKS') );
		if ( $hooks ){
			foreach( $hooks as $hook_name ){
				$core_hook = Charcoal_Factory::createObject( s($hook_name), s('core_hook'), s('Charcoal_ICoreHook') );
				Charcoal_CoreHook::register( s($hook_name), $core_hook );
			}
		}

		//=======================================
		// Register cache driver objects

		$cache_drivers = Charcoal_Profile::getArray( s('CACHE_DRIVERS') );
		if ( $cache_drivers ){
			foreach( $cache_drivers as $cache_driver_name ){
				$cache_driver = Charcoal_Factory::createObject( s($cache_driver_name), s('cache_driver'), s('Charcoal_ICacheDriver') );
				Charcoal_Cache::register( s($cache_driver_name), $cache_driver );
			}
		}

		//=======================================
		// 例外ハンドラの登録
		//
		self::callbackHook( i(Charcoal_EnumCoreHookStage::BEFORE_REG_EXCEPTION_HANDLERS) );

		$exception_handlers = Charcoal_Profile::getArray( s('EXCEPTION_HANDLERS') );
		if ( $exception_handlers ){
			foreach( $exception_handlers as $handler_name ) 	{
				$handler = Charcoal_Factory::createObject( s($handler_name), s('exception_handler'), s('Charcoal_IExceptionHandler') );
				self::callbackHook( i(Charcoal_EnumCoreHookStage::CREATE_EXCEPTION_HANDLER), s($handler_name) );
				Charcoal_ExceptionHandlerList::addExceptionHandler( $handler );
			}
		}

		self::callbackHook( i(Charcoal_EnumCoreHookStage::AFTER_REG_EXCEPTION_HANDLERS) );

		//=======================================
		// ロガー作成
		//
		self::callbackHook( i(Charcoal_EnumCoreHookStage::BEFORE_REG_USER_LOGGERS) );

		// プロファイルに設定されているロガーを取得
		$logger_names = Charcoal_Profile::getArray( s('LOG_LOGGERS') );
//		log_debug( 'system, debug', 'framework',"logger_names: $logger_names");

		// ロガーの登録
		if ( $logger_names ){
			foreach( $logger_names as $logger_name ){
				$registerd = Charcoal_Logger::isRegistered( s($logger_name) );
				if ( !$registerd ){
					$logger = Charcoal_Factory::createObject( s($logger_name), s('logger'), s('Charcoal_ILogger') );
					self::callbackHook( i(Charcoal_EnumCoreHookStage::CREATE_USER_LOGGER), s($logger_name) );
					Charcoal_Logger::register( s($logger_name), $logger );
				}
				else{
//					log_warning( "system,debug,error", 'framework',"Logger[$logger_name] is already registered!");
				}
			}
		}

		self::callbackHook( i(Charcoal_EnumCoreHookStage::AFTER_REG_USER_LOGGERS) );

		//=======================================
		// 外部ライブラリの使用
		//

		self::callbackHook( i(Charcoal_EnumCoreHookStage::BEFORE_REG_EXTLIB_DIR) );

		if ( Charcoal_Profile::getBoolean(s('USE_EXTLIB'))->isTrue() ){
			$lib_dirs = Charcoal_Profile::getArray( s('EXTLIB_DIR') );
			foreach( $lib_dirs as $dir ){
				$path = Charcoal_ResourceLocator::processMacro( s($dir) );
				add_include_path( $path->getValue() );
				self::callbackHook( i(Charcoal_EnumCoreHookStage::ADD_EXTLIB_DIR), s($path) );
			}
		}

		self::callbackHook( i(Charcoal_EnumCoreHookStage::AFTER_REG_EXTLIB_DIR) );

		//=======================================
		// セッションハンドラの作成
		//

		$use_session = Charcoal_Profile::getBoolean(s('USE_SESSION'))->isTrue();

		if ( $use_session )
		{
			self::callbackHook( i(Charcoal_EnumCoreHookStage::BEFORE_SET_SESSION_HANDLER) );

			// セッションハンドラ名の取得
			$session_handler_name = Charcoal_Profile::getString( s('SESSION_HANDLER_NAME') );

			if ( $session_handler_name )
			{
				// セッションハンドラの作成
				$session_handler = Charcoal_Factory::createObject( s($session_handler_name), s('session_handler'), s('Charcoal_ISessionHandler') );

				// コールバックの登録
				$class_name = get_class($session_handler);

				session_set_save_handler(
							array( $class_name, 'open' ), 
							array( $class_name, 'close' ), 
							array( $class_name, 'read' ), 
							array( $class_name, 'write' ), 
							array( $class_name, 'destroy' ), 
							array( $class_name, 'gc' )
					);
			}

			self::callbackHook( i(Charcoal_EnumCoreHookStage::AFTER_SET_SESSION_HANDLER) );
		}

		//=======================================
		// Create Session
		//

		// create session
		$session = new Charcoal_Session();

		if ( $use_session )
		{
			self::callbackHook( i(Charcoal_EnumCoreHookStage::BEFORE_START_SESSION) );

			// start session
			$session->start();
//			log_info( "system", 'framework','Session started' );

			// restore session
			$session->restore();
//			log_info( "system", 'framework','Session is restored.' );

			self::callbackHook( i(Charcoal_EnumCoreHookStage::AFTER_START_SESSION) );
		}

		//=======================================
		// Routing Rule
		//

		self::callbackHook( i(Charcoal_EnumCoreHookStage::BEFORE_ROUTING_RULE) );

		// get routers list from profile
		$routing_rule_name = Charcoal_Profile::getString( s('ROUTING_RULE'), s('') );

		// register routers
		$routing_rule = NULL;
		if ( !$routing_rule_name->isEmpty() ){
			$routing_rule = Charcoal_Factory::createObject( s($routing_rule_name), s('routing_rule'), s('Charcoal_IRoutingRule') );
		}

		self::callbackHook( i(Charcoal_EnumCoreHookStage::AFTER_ROUTING_RULE) );

		//=======================================
		// Router
		//

		if ( $routing_rule )
		{
			self::callbackHook( i(Charcoal_EnumCoreHookStage::BEFORE_ROUTER) );

			// get routers list from profile
			$router_names = Charcoal_Profile::getArray( s('ROUTERS') );

			// register routers
			if ( !$router_names->isEmpty() ){
				foreach( $router_names as $router_name ){
					$router = Charcoal_Factory::createObject( s($router_name), s('router'), s('Charcoal_IRouter') );

					$res = $router->route( $request, $routing_rule );
					if ( $res->isTrue() ){
						$proc_path = self::getRequestPath();
						log_debug( "debug,system", 'framework',"routed: proc_path=[$proc_path]" );
						break;
					}
				}
			}

			self::callbackHook( i(Charcoal_EnumCoreHookStage::AFTER_ROUTER) );
		}

		//=======================================
		// Procedureの作成
		//

		// プロシージャを作成
		self::callbackHook( i(Charcoal_EnumCoreHookStage::BEFORE_CREATE_PROCEDURE), s($proc_path) );

		try{
			$procedure = Charcoal_Factory::createObject( s($proc_path), s('procedure'), s('Charcoal_IProcedure') );
		}
		catch( Exception $e )
		{
			_catch( $e );

			_throw( new Charcoal_ProcedureNotFoundException( s($proc_path), $e ) );
		}

		self::callbackHook( i(Charcoal_EnumCoreHookStage::AFTER_CREATE_PROCEDURE), s($proc_path) );

		// procedure forwarding
		self::callbackHook( i(Charcoal_EnumCoreHookStage::BEFORE_PROCEDURE_FORWARD) );
		if ( $procedure->hasForwardTarget() ){
			// get forward target path
			$object_path = $procedure->getForwardTarget();
//			log_debug( "debug,system", 'framework',"procedure forward target:" . $object_path );

			self::callbackHook( i(Charcoal_EnumCoreHookStage::PRE_PROCEDURE_FORWARD), $object_path );

			// create target procedure
			$procedure = Charcoal_Factory::createObject( s($object_path->toString()), s('procedure'), s('Charcoal_IProcedure') );
//			log_debug( "debug,system", 'framework',"forward procedure created:" . $procedure );

			self::callbackHook( i(Charcoal_EnumCoreHookStage::POST_PROCEDURE_FORWARD), $object_path );
		}
		self::callbackHook( i(Charcoal_EnumCoreHookStage::AFTER_PROCEDURE_FORWARD) );

		if ( $procedure->isDebugMode() === TRUE && self::$debug_mode === FALSE ) {
			self::$debug_mode = TRUE;
//			log_debug( "debug,system", 'framework',"debug_mode=" . self::$debug_mode );
		}

		//=======================================
		// コンテナの作成と起動
		//

		self::callbackHook( i(Charcoal_EnumCoreHookStage::BEFORE_CREATE_CONTAINER) );

		// DIコンテナを作成
		Charcoal_DIContainer::createContainer( $procedure );

		self::callbackHook( i(Charcoal_EnumCoreHookStage::AFTER_CREATE_CONTAINER) );

		//=======================================
		// Requestオブジェクトの作成
		//

		// Requestオブジェクトを作成
		$response = Charcoal_Factory::createObject( s(CHARCOAL_RUNMODE), s('response'), s('Charcoal_IResponse') );

		//=======================================
		// レスポンスフィルタの作成
		//

		self::callbackHook( i(Charcoal_EnumCoreHookStage::BEFORE_REG_RESPONSE_FILTERS) );

		$response_filters = Charcoal_Profile::getArray( s('RESPONSE_FILTERS'), v(array()) );
		if ( !$response_filters->isEmpty() ){
			foreach( $response_filters as $filter_name ){
				$filter = Charcoal_Factory::createObject( s($filter_name), s('response_filter'), s('Charcoal_IResponseFilter') );
				self::callbackHook( i(Charcoal_EnumCoreHookStage::CREATE_RESPONSE_FILTER), s($filter_name) );
				$response->addResponsefilter( $filter );
			}
		}

		self::callbackHook( i(Charcoal_EnumCoreHookStage::AFTER_REG_RESPONSE_FILTERS) );

		//=======================================
		// ブートストラップ完了
		//

		self::callbackHook( i(Charcoal_EnumCoreHookStage::END_OF_BOOTSTRAP) );

		//=======================================
		// Procedureの実行
		//

		self::callbackHook( i(Charcoal_EnumCoreHookStage::BEFORE_EXECUTE_PROCEDURES) );

		// プロシージャの実行
		while( $procedure )
		{
			$path = $procedure->getObjectPath()->getVirtualPath();

			self::callbackHook( i(Charcoal_EnumCoreHookStage::PRE_EXECUTE_PROCEDURE), s($path) );
			$procedure->execute( $request, $response, $session );
			self::callbackHook( i(Charcoal_EnumCoreHookStage::POST_EXECUTE_PROCEDURE), s($path) );

			// プロシージャをスタックから取得
			$procedure = self::popProcedure();
		}

		self::callbackHook( i(Charcoal_EnumCoreHookStage::AFTER_EXECUTE_PROCEDURES) );

		//=======================================
		// 終了処理
		//

		self::callbackHook( i(Charcoal_EnumCoreHookStage::START_OF_SHUTDOWN) );
		self::callbackHook( i(Charcoal_EnumCoreHookStage::BEFORE_SAVE_SESSION) );

		// セッション情報の保存
		if ( $use_session )
		{
			// セッションを保存
			$session->save();
			$session->close();
		}

		self::callbackHook( i(Charcoal_EnumCoreHookStage::AFTER_SAVE_SESSION) );

		//=======================================
		// コンテナの破棄
		//

		self::callbackHook( i(Charcoal_EnumCoreHookStage::BEFORE_DESTROY_CONTAINER) );

		Charcoal_DIContainer::destroy();

		self::callbackHook( i(Charcoal_EnumCoreHookStage::AFTER_DESTROY_CONTAINER) );

		// 終了メッセージ
		self::callbackHook( i(Charcoal_EnumCoreHookStage::BEFORE_TERMINATE_LOGGERS) );


		self::callbackHook( i(Charcoal_EnumCoreHookStage::AFTER_TERMINATE_LOGGERS) );
		self::callbackHook( i(Charcoal_EnumCoreHookStage::END_OF_SHUTDOWN) );

	}

	/**
	 *	フレームワークを起動
	 */
	public static function run()
	{
		self::$start_time = Charcoal_Benchmark::nowTime();

		try{
			try{
				self::_run();
			}
			catch( Charcoal_ProcedureNotFoundException $e )
			{
				_catch( $e );

				switch( CHARCOAL_RUNMODE ){
				// ランモードがhttpの時は404エラー
				case 'http':
					_throw( new Charcoal_HttpException(i(404)) );
					break;
				// それ以外の場合はリスロー
				default:
					_throw( $e );
					break;
				}
			}
		}
		catch ( Charcoal_CharcoalException $e )
		{
			log_debug( "system,error,debug", "exception", "catch $e", Charcoal_EnumEchoFlag::ECHO_EXCEPTION );
			_catch( $e );

			// 既に出力された内容をクリア
			$clear_buffer = ub( Charcoal_Profile::getBoolean( s('DEBUG_CLEAR_BUFFER'), b(FALSE) ) );
			if ( $clear_buffer ){
				ob_clean();
			}

			// ループを防止するため、これ以降のエラーは以前のエラーハンドラで処理する
			restore_error_handler();
			restore_exception_handler();

			$handled = Charcoal_ExceptionHandlerList::handleFrameworkException( $e );
//			log_debug( "system,debug,error", 'framework',"exception[$e] handled=$handled" );

			// デバッグトレースまたは500.htmlを表示
			$handled = b($handled);
			if ( $handled->isFalse() )
			{
				self::renderExceptionFinally( $e );
			}

			Charcoal_Logger::flush();
		}
		catch( Exception $e )
		{
//			log_debug( "system,error,debug", "exception", "catch $e", Charcoal_EnumEchoFlag::ECHO_EXCEPTION );
			_catch( $e );

			// ループを防止するため、これ以降のエラーは以前のエラーハンドラで処理する
//			restore_error_handler();
//			restore_exception_handler();

			$handled = Charcoal_ExceptionHandlerList::handleException( $e );
//			log_debug( "system,debug,error", 'framework',"exception[$e] handled=$handled" );

			// デバッグトレースまたは500.htmlを表示
			$handled = b($handled);
			if ( $handled->isFalse() )
			{
				self::renderExceptionFinally( $e );
			}

			Charcoal_Logger::flush();
		}
	}


	/**
	 *	Render not handled exception
	 */
	public static function renderExceptionFinally( Exception $e )
	{
		$rendered = FALSE;

		// デバッグトレースがONかつデバッグモードONならデバッグトレースを表示、デバッグモードOFFなら500.htmlを表
		log_info( 'system, debug', 'framework',"print_debugtrace:" . self::$print_debugtrace );
		log_info( 'system, debug', 'framework',"debug_mode:" . self::$debug_mode );

		if ( self::$print_debugtrace && self::$debug_mode )
		{
			// Create Debug Trace Renderer
			$debugtrace_renderer_name = Charcoal_Profile::getString( s('DEBUGTRACE_RENDERER') );
//			log_info( 'system, debug', 'framework',"debugtrace_renderer_name: $debugtrace_renderer_name" );

			$debugtrace_renderer = NULL;

			if ( $debugtrace_renderer_name ){

				try{
					$debugtrace_renderer = Charcoal_Factory::createObject( s($debugtrace_renderer_name), s('debugtrace_renderer'), s('Charcoal_IDebugtraceRenderer') );
//					log_info( 'system, debug', 'framework',"debugtrace_renderer[$debugtrace_renderer_name] created." );
				}
				catch ( Exception $e )
				{
					_catch( $e );
//					log_error( 'system, debug, error', 'framework',"debugtrace_renderer[$debugtrace_renderer] creation failed." );
				}
			}

			// render exception
			if ( $debugtrace_renderer ){
				try{
					// Render exception
					$debugtrace_renderer->render( $e, s('Oops! Exception was not handled!') );

					$rendered = TRUE;
//					log_info( 'system, debug', 'framework',"debugtrace was rendered." );
				}
				catch ( Exception $e )
				{
					_catch( $e );
//					log_error( 'system, debug, error', 'framework',"debugtrace_renderer[$debugtrace_renderer] rendering failed." );
				}
			}
		}

		// Show something if debugtrace rendering failed
		if ( !$rendered ){
			log_debug( 'system, debug', 'framework',"debugtrace was not rendered." );
			switch( CHARCOAL_RUNMODE ){
			case 'http':
				log_debug( 'system, debug', 'framework',"showing 404 html." );
				self::showHttpErrorDocument( i(500) );
				break;
			case 'shell':
			default:
				echo "Exception was not handled: $e";
				break;
			}
		}
	}

}

return __FILE__;