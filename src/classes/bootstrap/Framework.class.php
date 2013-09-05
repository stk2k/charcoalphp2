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
	static $proc_stack;
	static $request;
	static $debug_mode;
	static $hook_stage;
	static $loaded_files;

	/**
	 *	load source file
	 */
	public static function loadSourceFile( $path )
	{
		Charcoal_ParamTrait::checkString( 1, $path );

		if ( !is_file($path) ){
			_throw( new Charcoal_FileNotFoundException( $path ) );
		}

		if ( !is_readable($path) ){
			_throw( new Charcoal_FileNotReadableException( $path ) );
		}

		require( $path );
		self::$loaded_files[] = $path;
//		log_info( "system,debug,loaded_files","loaded source file: [$file]", 'framework' );
	}

	/**
	 *	get loaded source file
	 */
	public static function getLoadedSourceFiles()
	{
		return self::$loaded_files ? self::$loaded_files : array();
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
	public static function setHookStage( Charcoal_Integer $hook_stage, Charcoal_Boolean $flush_now = NULL, Charcoal_Object $data = NULL )
	{
		self::$hook_stage = $hook_stage;

		$msg = ( $data ) ? new Charcoal_CoreHookMessage($hook_stage, $data) : new Charcoal_CoreHookMessage($hook_stage);

		if ( $flush_now )
			Charcoal_CoreHook::pushMessage( $msg, $flush_now );
		else
			Charcoal_CoreHook::pushMessage( $msg );
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
//			log_info( 'system,debug,error',"エラードキュメント($html_file_path)は存在しません。", 'framework');

			// プロジェクト以下のerror_docsを検索
			$html_file_path = Charcoal_ResourceLocator::getProjectPath( s('error_docs') , s($html_file) );
			if ( !is_file($html_file_path) ){
//				log_debug( 'system,debug,error',"エラードキュメント($html_file_path)は存在しません。", 'framework');

				// フレームワーク以下のerror_docsを検索
				$html_file_path = Charcoal_ResourceLocator::getFrameworkPath( s('error_docs') , s($html_file) );
				if ( !is_file($html_file_path) ){
//					log_warning( 'system,debug,error',"エラードキュメント($html_file_path)は存在しません。", 'framework');
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
	 *	デバッグモードか
	 */
	public static function isDebugMode()
	{
		return self::$debug_mode;
	}

	/**
	 *	get version info about framework
	 *	
	 *	@return Charcoal_FrameworkVersion              version info about framework
	 */
	public static function getVersion()
	{
		return new Charcoal_FrameworkVersion();
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
	private static function _run( $debug_mode )
	{
		self::$debug_mode = $debug_mode;

		//==================================================================
		// プロファイルを読み込む
		Charcoal_Profile::load( 'default', $debug_mode );
		Charcoal_Profile::load( CHARCOAL_PROFILE, $debug_mode );

		self::$debug_mode = Charcoal_Profile::getBoolean( s('DEBUG_MODE'), b(FALSE) );
//		log_debug( "debug,system","debug_mode=" . self::$debug_mode, 'framework' );

		//=======================================
		// Start bootstrap

		self::setHookStage( i(Charcoal_EnumCoreHookStage::START_OF_BOOTSTRAP) );

		//=======================================
		// フレームワーク初期化処理
		//

		self::setHookStage( i(Charcoal_EnumCoreHookStage::BEFORE_INIT_FRAMEWORK) );

		// タイムアウトを指定
		if ( !ini_get('safe_mode') ){
			$timeout = Charcoal_Profile::getInteger( s('SCRIPT_TIMEOUT'), i(600) )->getValue();
			set_time_limit( $timeout );
		}

		self::setHookStage( i(Charcoal_EnumCoreHookStage::AFTER_INIT_FRAMEWORK) );

		//=======================================
		// クラスローダの登録
		//

		self::setHookStage( i(Charcoal_EnumCoreHookStage::BEFORE_REG_CLASS_LOADERS) );

		$framework_class_loader = Charcoal_Factory::createClassLoader( s('framework') );
		self::setHookStage( i(Charcoal_EnumCoreHookStage::CREATE_FRAMEWORK_CLASS_LOADER), b(FALSE), s($framework_class_loader) );

		Charcoal_ClassLoader::addClassLoader( $framework_class_loader );
		self::setHookStage( i(Charcoal_EnumCoreHookStage::REG_FRAMEWORK_CLASS_LOADER), b(FALSE), s($framework_class_loader) );

		try{
			$class_loaders = Charcoal_Profile::getArray( s('CLASS_LOADERS') );
			if (  $class_loaders ){
				foreach( $class_loaders as $loader_name ) {
					$loader = Charcoal_Factory::createClassLoader( s($loader_name) );
					self::setHookStage( i(Charcoal_EnumCoreHookStage::CREATE_CLASS_LOADER), b(FALSE), s($loader_name) );
					Charcoal_ClassLoader::addClassLoader( $loader );
					self::setHookStage( i(Charcoal_EnumCoreHookStage::REG_CLASS_LOADER), b(FALSE), s($loader_name) );
				}
			}
		}
		catch( Charcoal_CreateClassLoaderException $ex )
		{
			_catch( $ex );

			_throw( new Charcoal_FrameworkBootstrapException( 'failed to load class loader:'.$ex->getClassLoaderPath(), $ex ) );
		}

		// register framework class loader
		if ( !spl_autoload_register('Charcoal_ClassLoader::loadClass',false,false) )
		{
			log_fatal( "debug,system,error", 'framework', "registering master class loader failed." );
			exit;
		}

		self::setHookStage( i(Charcoal_EnumCoreHookStage::AFTER_REG_CLASS_LOADERS) );

		//=======================================
		// Requestパラメータの取得
		//

		// Requestオブジェクトを作成
		$request = Charcoal_Factory::createObject( s(CHARCOAL_RUNMODE), s('request'), v(array()), s('Charcoal_IRequest') );
		self::$request = $request;
//		log_debug( "debug,system","request object created: " . print_r($request,true), 'framework' );

		$proc_path = self::getRequestPath();
//		log_debug( "debug,system","proc_path=" . $proc_path, 'framework' );

		// if procedure path is not specified in url, forward the procedure to DEFAULT_PROCPATH in profile.ini
		if ( strlen($proc_path) === 0 ){
			$proc_path = Charcoal_Profile::getString( s('DEFAULT_PROCPATH') );
		}

		// flush core hook messages
		Charcoal_CoreHook::flushMessages();

		//=======================================
		// 外部ライブラリの使用
		//

		self::setHookStage( i(Charcoal_EnumCoreHookStage::BEFORE_REG_EXTLIB_DIR), b(TRUE) );

		if ( Charcoal_Profile::getBoolean(s('USE_EXTLIB'))->isTrue() ){
			$lib_dirs = Charcoal_Profile::getArray( s('EXTLIB_DIR') );
			foreach( $lib_dirs as $dir ){
				$path = Charcoal_ResourceLocator::processMacro( s($dir) );
				add_include_path( $path->getValue() );
				self::setHookStage( i(Charcoal_EnumCoreHookStage::ADD_EXTLIB_DIR), b(TRUE), s($path) );
			}
		}

		self::setHookStage( i(Charcoal_EnumCoreHookStage::AFTER_REG_EXTLIB_DIR), b(TRUE) );

		//=======================================
		// セッションハンドラの作成
		//

		$use_session = Charcoal_Profile::getBoolean(s('USE_SESSION'))->isTrue();

		if ( $use_session )
		{
			self::setHookStage( i(Charcoal_EnumCoreHookStage::BEFORE_SET_SESSION_HANDLER), b(TRUE) );

			// セッションハンドラ名の取得
			$session_handler_name = Charcoal_Profile::getString( s('SESSION_HANDLER_NAME') );

			if ( $session_handler_name )
			{
				// セッションハンドラの作成
				$session_handler = Charcoal_Factory::createObject( s($session_handler_name), s('session_handler'), v(array()), s('Charcoal_ISessionHandler') );

				session_set_save_handler(
							array( $session_handler, 'open' ), 
							array( $session_handler, 'close' ), 
							array( $session_handler, 'read' ), 
							array( $session_handler, 'write' ), 
							array( $session_handler, 'destroy' ), 
							array( $session_handler, 'gc' )
					);
			}

			self::setHookStage( i(Charcoal_EnumCoreHookStage::AFTER_SET_SESSION_HANDLER), b(TRUE) );
		}

		//=======================================
		// Create Session
		//

		// create session
		$session = new Charcoal_Session();

		if ( $use_session )
		{
			self::setHookStage( i(Charcoal_EnumCoreHookStage::BEFORE_START_SESSION), b(TRUE) );

			// start session
			$session->start();
//			log_info( "system",'Session started', 'framework' );

			// restore session
			$session->restore();
//			log_info( "system",'Session is restored.', 'framework' );

			self::setHookStage( i(Charcoal_EnumCoreHookStage::AFTER_START_SESSION), b(TRUE) );
		}

		//=======================================
		// Routing Rule
		//

		self::setHookStage( i(Charcoal_EnumCoreHookStage::BEFORE_ROUTING_RULE), b(TRUE) );

		// get routers list from profile
		$routing_rule_name = Charcoal_Profile::getString( s('ROUTING_RULE'), s('') );

		// register routers
		$routing_rule = NULL;
		if ( !$routing_rule_name->isEmpty() ){
			$routing_rule = Charcoal_Factory::createObject( s($routing_rule_name), s('routing_rule'), v(array()), s('Charcoal_IRoutingRule') );
		}

		self::setHookStage( i(Charcoal_EnumCoreHookStage::AFTER_ROUTING_RULE), b(TRUE) );

		//=======================================
		// Router
		//

		if ( $routing_rule )
		{
			self::setHookStage( i(Charcoal_EnumCoreHookStage::BEFORE_ROUTER), b(TRUE) );

			// get routers list from profile
			$router_names = Charcoal_Profile::getArray( s('ROUTERS') );

			// register routers
			if ( !$router_names->isEmpty() ){
				foreach( $router_names as $router_name ){
					$router = Charcoal_Factory::createObject( s($router_name), s('router'), v(array()), s('Charcoal_IRouter') );

					$res = $router->route( $request, $routing_rule );
					if ( $res->isTrue() ){
						$proc_path = self::getRequestPath();
						log_debug( "debug,system","routed: proc_path=[$proc_path]", 'framework' );
						break;
					}
				}
			}

			self::setHookStage( i(Charcoal_EnumCoreHookStage::AFTER_ROUTER), b(TRUE) );
		}

		//=======================================
		// Procedureの作成
		//

		// プロシージャを作成
		self::setHookStage( i(Charcoal_EnumCoreHookStage::BEFORE_CREATE_PROCEDURE), b(TRUE), s($proc_path) );

		try{
			$procedure = Charcoal_Factory::createObject( s($proc_path), s('procedure'), v(array()), s('Charcoal_IProcedure') );
		}
		catch( Exception $e )
		{
			_catch( $e );

			_throw( new Charcoal_ProcedureNotFoundException( $proc_path, $e ) );
		}

		self::setHookStage( i(Charcoal_EnumCoreHookStage::AFTER_CREATE_PROCEDURE), b(TRUE), s($proc_path) );

		// procedure forwarding
		self::setHookStage( i(Charcoal_EnumCoreHookStage::BEFORE_PROCEDURE_FORWARD), b(TRUE) );
		if ( $procedure->hasForwardTarget() ){
			// get forward target path
			$object_path = $procedure->getForwardTarget();
//			log_debug( "debug,system","procedure forward target:" . $object_path, 'framework' );

			self::setHookStage( i(Charcoal_EnumCoreHookStage::PRE_PROCEDURE_FORWARD), b(TRUE), $object_path );

			// create target procedure
			$procedure = Charcoal_Factory::createObject( s($object_path->toString()), s('procedure'), s('Charcoal_IProcedure') );
//			log_debug( "debug,system","forward procedure created:" . $procedure, 'framework' );

			self::setHookStage( i(Charcoal_EnumCoreHookStage::POST_PROCEDURE_FORWARD), b(TRUE), $object_path );
		}
		self::setHookStage( i(Charcoal_EnumCoreHookStage::AFTER_PROCEDURE_FORWARD), b(TRUE) );

		//=======================================
		// コンテナの作成と起動
		//

		self::setHookStage( i(Charcoal_EnumCoreHookStage::BEFORE_CREATE_CONTAINER), b(TRUE) );

		// DIコンテナを作成
		Charcoal_DIContainer::createContainer();

		self::setHookStage( i(Charcoal_EnumCoreHookStage::AFTER_CREATE_CONTAINER), b(TRUE) );

		//=======================================
		// create response object
		//

		$response = Charcoal_Factory::createObject( s(CHARCOAL_RUNMODE), s('response'), v(array()), s('Charcoal_IResponse') );

		//=======================================
		// ブートストラップ完了
		//

		self::setHookStage( i(Charcoal_EnumCoreHookStage::END_OF_BOOTSTRAP), b(TRUE) );

		//=======================================
		// Procedureの実行
		//

		self::setHookStage( i(Charcoal_EnumCoreHookStage::BEFORE_EXECUTE_PROCEDURES), b(TRUE) );

		// プロシージャの実行
		while( $procedure )
		{
			$path = $procedure->getObjectPath()->getVirtualPath();

			self::setHookStage( i(Charcoal_EnumCoreHookStage::PRE_EXECUTE_PROCEDURE), b(TRUE), s($path) );
			$procedure->execute( $request, $response, $session );
			self::setHookStage( i(Charcoal_EnumCoreHookStage::POST_EXECUTE_PROCEDURE), b(TRUE), s($path) );

			// プロシージャをスタックから取得
			$procedure = self::popProcedure();
		}

		self::setHookStage( i(Charcoal_EnumCoreHookStage::AFTER_EXECUTE_PROCEDURES), b(TRUE) );

		//=======================================
		// 終了処理
		//

		self::setHookStage( i(Charcoal_EnumCoreHookStage::START_OF_SHUTDOWN), b(TRUE) );
		self::setHookStage( i(Charcoal_EnumCoreHookStage::BEFORE_SAVE_SESSION), b(TRUE) );

		$response->terminate();

		// セッション情報の保存
		if ( $use_session )
		{
			// セッションを保存
			$session->save();
			$session->close();
		}

		self::setHookStage( i(Charcoal_EnumCoreHookStage::AFTER_SAVE_SESSION), b(TRUE) );

		//=======================================
		// コンテナの破棄
		//

		self::setHookStage( i(Charcoal_EnumCoreHookStage::BEFORE_DESTROY_CONTAINER), b(TRUE) );

		Charcoal_DIContainer::destroy();

		self::setHookStage( i(Charcoal_EnumCoreHookStage::AFTER_DESTROY_CONTAINER), b(TRUE) );

		// 終了メッセージ
		self::setHookStage( i(Charcoal_EnumCoreHookStage::BEFORE_TERMINATE_LOGGERS), b(TRUE) );


		self::setHookStage( i(Charcoal_EnumCoreHookStage::AFTER_TERMINATE_LOGGERS), b(TRUE) );
		self::setHookStage( i(Charcoal_EnumCoreHookStage::END_OF_SHUTDOWN), b(TRUE) );

	}

	/**
	 *	フレームワークを起動
	 */
	public static function run( $debug_mode = FALSE )
	{
		Charcoal_Benchmark::start( 'framework::run()' );

		try{
			try{
				ob_start();
				self::_run( $debug_mode );
				ob_end_flush();
			}
			catch( Charcoal_ProcedureNotFoundException $ex )
			{
				_catch( $ex );

				switch( CHARCOAL_RUNMODE ){
				// ランモードがhttpの時は404エラー
				case 'http':
					_throw( new Charcoal_HttpException( 404, $ex ) );
					break;
				// それ以外の場合はリスロー
				default:
					_throw( $ex );
					break;
				}
			}
		}
		catch ( Charcoal_CharcoalException $e )
		{
			_catch( $e );

			// restore error handlers for avoiding infinite loop
			restore_error_handler();
			restore_exception_handler();

			// call error handlers which are defined in profile.ini
			$handled = Charcoal_ExceptionHandlerList::handleFrameworkException( $e );

			// display debugtrace
			$handled = b($handled);
			if ( $handled->isFalse() || self::$debug_mode->isTrue() )
			{
				self::renderExceptionFinally( $e );
			}

			Charcoal_Logger::flush();
		}
		catch( Exception $e )
		{
			_catch( $e );

			// restore error handlers for avoiding infinite loop
			restore_error_handler();
			restore_exception_handler();

			$handled = Charcoal_ExceptionHandlerList::handleException( $e );

			// display debugtrace
			$handled = b($handled);
			if ( $handled->isFalse() || self::$debug_mode->isTrue() )
			{
				self::renderExceptionFinally( $e );
			}

			Charcoal_Logger::flush();
		}

		// finally process
		$score = Charcoal_Benchmark::stop( 'framework::run()' );
		log_debug( 'system, debug', "total framework process time: [$score] msec" );

		Charcoal_Logger::terminate();
	}


	/**
	 *	Render not handled exception
	 */
	public static function renderExceptionFinally( Exception $e )
	{
		$rendered = FALSE;

		// デバッグモードONならデバッグトレースを表示、デバッグモードOFFなら500.htmlを表
		log_info( 'system, debug', 'debug_mode:' . self::$debug_mode, 'framework' );

		// Render exception
		if ( self::$debug_mode->isTrue() ) {
			$rendered = Charcoal_DebugTraceRendererList::render( $e );
		}

		// Show something if debugtrace rendering failed
		if ( !$rendered || $rendered->isFalse() ){
			log_debug( 'system, debug','debugtrace was not rendered.', 'framework' );
			switch( CHARCOAL_RUNMODE ){
			case 'http':
				log_debug( 'system, debug', 'showing 500 html(internal server error).', 'framework' );

				self::showHttpErrorDocument( i(500) );
				break;
			case 'shell':
			default:
				echo 'Exception was not handled: ' . $e;
				break;
			}
		}
	}

}

