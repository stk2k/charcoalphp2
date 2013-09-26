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
	static $proc_path;
	static $request;
	static $hook_stage;
	static $loaded_files;
	static $exception_handlers;
	static $debugtrace_renderers;
	static $loggers;
	static $corehooks;
	static $cache_drivers;


	/**
	 *	load source file
	 */
	public static function loadSourceFile( $path )
	{
//		Charcoal_ParamTrait::checkString( 1, $path );
/*
		if ( !is_file($path) ){
			_throw( new Charcoal_FileNotFoundException( $path ) );
		}

		if ( !is_readable($path) ){
			_throw( new Charcoal_FileNotReadableException( $path ) );
		}
*/

		include( $path );
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
	public static function pushProcedure( $procedure )
	{
		$p1 = Charcoal_ParamTrait::checkStringOrObject( 1, 'Charcoal_IProcedure', $procedure );

		if ( $p1 === 'string' || $p1 === 'Charcoal_String' ){
			try{
				$procedure = $sandbox->createObject( $procedure, 'procedure', array(), 'Charcoal_IProcedure' );
			}
			catch( Exception $e )
			{
				_catch( $e );
				_throw( new Charcoal_ProcedureNotFoundException( $procedure, $e ) );
			}
		}

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
	 * 
	 * @param int $hook_stage      hook stage
	 * @param mixed $data          additional data
	 */
	public static function setHookStage( $hook_stage, $data = NULL )
	{
		Charcoal_ParamTrait::checkInteger( 1, $hook_stage );
		Charcoal_ParamTrait::checkObject( 2, $data, TRUE );

		self::$hook_stage = $hook_stage;

		self::$corehooks->processMessage( $hook_stage, $data );
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
	public static function showHttpErrorDocument( $status_code )
	{
		Charcoal_ParamTrait::checkInteger( 1, $status_code );

		$status_code = ui($status_code);

		// HTML
		$html_file = $status_code . '.html';

		// アプリケーション以下のerror_docsを検索
		$html_file_path = Charcoal_ResourceLocator::getApplicationPath( 'error_docs', $html_file );
		if ( !is_file($html_file_path) ){
//			log_info( 'system,debug,error',"エラードキュメント($html_file_path)は存在しません。", 'framework');

			// プロジェクト以下のerror_docsを検索
			$html_file_path = Charcoal_ResourceLocator::getProjectPath( 'error_docs' , $html_file );
			if ( !is_file($html_file_path) ){
//				log_debug( 'system,debug,error',"エラードキュメント($html_file_path)は存在しません。", 'framework');

				// フレームワーク以下のerror_docsを検索
				$html_file_path = Charcoal_ResourceLocator::getFrameworkPath( 'error_docs', $html_file );
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
		$status_message_file = Charcoal_ResourceLocator::getFrameworkPath( 'preset' , 'status_messages.ini' );
		if ( !is_file($status_message_file) ){
//			log_warning( 'system,debug,error', 'framework',"ステータスメッセージファイル($status_message_file)が存在しません。");
		}
		$status_messages = parse_ini_file($status_message_file,FALSE);
		if ( FALSE === $status_messages ){
//			log_warning( 'system,debug,error', 'framework',"ステータスメッセージファイル($status_message_file)の読み込みに失敗しました。");
		}
		$header_msg = isset($status_messages[$status_code]) ? $status_messages[$status_code] : '';

		// ヘッダ出力
		header( "HTTP/1.0 $status_code $header_msg", true, $status_code );

//		log_error( 'system,error', 'framework',"HTTP/1.0 $status_code $header_msg");
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
		return self::$proc_path;
	}

	/**
	 * execute exception handlers
	 * 
	 * @param Charcoal_CharcoalException $e     exception to handle
	 * 
	 * @return boolean        TRUE means the exception is handled, otherwise FALSE
	 */
	public static function handleException( $e )
	{
		Charcoal_ParamTrait::checkException( 1, $e );

		return self::$exception_handlers->handleException( $e );
	}

	/*
	 * write one message
	 */
	public function writeLog( $target, $message,  $tag = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $target );
//		Charcoal_ParamTrait::checkString( 2, $message );
//		Charcoal_ParamTrait::checkString( 3, $tag, TRUE );

		if ( self::$loggers ){
			self::$loggers->writeLog( $target, $message,  $tag );
		}
		else{
			echo $message . eol();
		}
	}

	/**
	 * Get non-typed data which is associated with a string key
	 *
	 * @param Charcoal_String $key         The key of the item to retrieve.
	 *
	 * @return mixed                       cache data
	 */
	public  function getCache( $key )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );

		return self::$cache_drivers->getCache( $key );
	}

	/**
	 * Save a value to cache
	 *
	 * @param Charcoal_String $key         The key under which to store the value.
	 * @param Charcoal_Object $value       value to store
	 * @param Charcoal_Integer $duration   specify expiration span which the cache will be removed.
	 */
	public function setCache( $key, $value, $duration = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkObject( 2, $value );
//		Charcoal_ParamTrait::checkInteger( 3, $duration, TRUE );

		return self::$cache_drivers->setCache( $key, $value, $duration );
	}

	/**
	 * Remove a cache data
	 *
	 * @param Charcoal_String $key         The key of the item to remove. Shell wildcards are accepted.
	 * @param Charcoal_Boolean $regEx      specify regular expression in $key parameter, default is NULL which means FALSE.
	 */
	public function deleteCache( $key )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );

		self::$cache_drivers->deleteCache( $key );
	}

	/*
	 *	フレームワークの実行（実体）
	 */
	private static function _run( $sandbox )
	{
		Charcoal_ParamTrait::checkSandbox( 1, $sandbox );

		//==================================================================
		// create exception handler list
		self::$exception_handlers = new Charcoal_ExceptionHandlerList( $sandbox );

		//==================================================================
		// create debugtrace renderder list
		self::$debugtrace_renderers = new Charcoal_DebugTraceRendererList( $sandbox );

		//==================================================================
		// create logger list
		self::$loggers = new Charcoal_LoggerList( $sandbox );

		//==================================================================
		// create core hook list
		self::$corehooks = new Charcoal_CoreHookList( $sandbox );

		//==================================================================
		// create cache driver list
		self::$cache_drivers = new Charcoal_CacheDriverList( $sandbox );

		//==================================================================
		// load sandbox

		Charcoal_Benchmark::start();

		$profile = $sandbox->load();

		$score = Charcoal_Benchmark::stop();
		log_debug( 'system, debug', "sandbox profile loading time: [$score] msec" );

		//=======================================
		// Start bootstrap

		self::setHookStage( Charcoal_EnumCoreHookStage::START_OF_BOOTSTRAP );

		//=======================================
		// フレームワーク初期化処理
		//

		self::setHookStage( Charcoal_EnumCoreHookStage::BEFORE_INIT_FRAMEWORK );

		// タイムアウトを指定
		if ( !ini_get('safe_mode') ){
			$timeout = $profile->getInteger( 'SCRIPT_TIMEOUT', 600 );
			set_time_limit( $timeout->unbox() );
		}

		self::setHookStage( Charcoal_EnumCoreHookStage::AFTER_INIT_FRAMEWORK );

		//=======================================
		// クラスローダの登録
		//

		self::setHookStage( Charcoal_EnumCoreHookStage::BEFORE_REG_CLASS_LOADERS );

		$framework_class_loader = $sandbox->createClassLoader( 'framework' );
		self::setHookStage( Charcoal_EnumCoreHookStage::CREATE_FRAMEWORK_CLASS_LOADER, $framework_class_loader );

		Charcoal_ClassLoader::addClassLoader( $framework_class_loader );
		self::setHookStage( Charcoal_EnumCoreHookStage::REG_FRAMEWORK_CLASS_LOADER, $framework_class_loader );

		try{
			$class_loaders = $profile->getArray( 'CLASS_LOADERS' );
			if ( $class_loaders ){
				foreach( $class_loaders as $loader_name ) {
					if ( strlen($loader_name) === 0 )    continue;

					$loader = $sandbox->createClassLoader( $loader_name );
					self::setHookStage( Charcoal_EnumCoreHookStage::CREATE_CLASS_LOADER, $loader_name );
					Charcoal_ClassLoader::addClassLoader( $loader );
					self::setHookStage( Charcoal_EnumCoreHookStage::REG_CLASS_LOADER, $loader_name );
				}
			}
		}
		catch( Charcoal_CreateClassLoaderException $ex )
		{
			_catch( $ex );

			_throw( new Charcoal_FrameworkBootstrapException( 'failed to load class loader:'.$ex->getClassLoaderPath(), $ex ) );
		}

		// register framework class loader
		if ( !spl_autoload_register('Charcoal_ClassLoader::loadClass',false) )
		{
			log_fatal( "debug,system,error", 'framework', "registering master class loader failed." );
			_throw( new Charcoal_ClassLoaderRegistrationException( 'framework' ) );
		}

		self::setHookStage( Charcoal_EnumCoreHookStage::AFTER_REG_CLASS_LOADERS );

		//=======================================
		// Requestパラメータの取得
		//

		// Requestオブジェクトを作成
		$request = $sandbox->createObject( CHARCOAL_RUNMODE, 'request', array(), 'Charcoal_IRequest' );
		self::$request = $request;
//		log_debug( "debug,system","request object created: " . print_r($request,true), 'framework' );

		self::$proc_path = $request->getProcedurePath();
//		log_debug( "debug,system","proc_path=" . $proc_path, 'framework' );

		// if procedure path is not specified in url, forward the procedure to DEFAULT_PROCPATH in profile.ini
		if ( strlen(self::$proc_path) === 0 ){
			self::$proc_path = $profile->getString( 'DEFAULT_PROCPATH' );
		}

		$sandbox->getEnvironment()->set( '%REQUEST_PATH%', self::$proc_path );

		//=======================================
		// 外部ライブラリの使用
		//

		self::setHookStage( Charcoal_EnumCoreHookStage::BEFORE_REG_EXTLIB_DIR );

		if ( $profile->getBoolean( 'USE_EXTLIB', FALSE ) ){
			$lib_dirs = $profile->getArray( 'EXTLIB_DIR' );
			if ( $lib_dirs ){
				foreach( $lib_dirs as $dir ){
					if ( strlen($dir) === 0 )    continue;

					$path = Charcoal_ResourceLocator::processMacro( $dir );
					add_include_path( $path );
					self::setHookStage( Charcoal_EnumCoreHookStage::ADD_EXTLIB_DIR, $path );
				}
			}
		}

		self::setHookStage( Charcoal_EnumCoreHookStage::AFTER_REG_EXTLIB_DIR );

		//=======================================
		// セッションハンドラの作成
		//

		$use_session = $profile->getBoolean( 'USE_SESSION', FALSE );

		if ( $use_session->isTrue() )
		{
			self::setHookStage( Charcoal_EnumCoreHookStage::BEFORE_SET_SESSION_HANDLER );

			// セッションハンドラ名の取得
			$session_handler_name = $profile->getString( 'SESSION_HANDLER_NAME' );

			if ( !$session_handler_name->isEmpty() )
			{
				// セッションハンドラの作成
				$session_handler = $sandbox->createObject( $session_handler_name, 'session_handler', array(), 'Charcoal_ISessionHandler' );

				session_set_save_handler(
							array( $session_handler, 'open' ), 
							array( $session_handler, 'close' ), 
							array( $session_handler, 'read' ), 
							array( $session_handler, 'write' ), 
							array( $session_handler, 'destroy' ), 
							array( $session_handler, 'gc' )
					);
			}

			self::setHookStage( Charcoal_EnumCoreHookStage::AFTER_SET_SESSION_HANDLER );
		}

		//=======================================
		// Create Session
		//

		$session = NULL;
		if ( $use_session->isTrue() )
		{
			// create session
			$session = new Charcoal_Session();

			self::setHookStage( Charcoal_EnumCoreHookStage::BEFORE_START_SESSION );

			// start session
			$session->start();
//			log_info( "system",'Session started', 'framework' );

			// restore session
			$session->restore();
//			log_info( "system",'Session is restored.', 'framework' );

			self::setHookStage( Charcoal_EnumCoreHookStage::AFTER_START_SESSION );
		}

		//=======================================
		// Routing Rule
		//

		self::setHookStage( Charcoal_EnumCoreHookStage::BEFORE_ROUTING_RULE );

		// get routers list from profile
		$routing_rule_name = $profile->getString( 'ROUTING_RULE' );

		// register routers
		$routing_rule = NULL;
		if ( !$routing_rule_name->isEmpty()){
			$routing_rule = $sandbox->createObject( $routing_rule_name, 'routing_rule', array(), 'Charcoal_IRoutingRule' );
		}

		self::setHookStage( Charcoal_EnumCoreHookStage::AFTER_ROUTING_RULE );

		//=======================================
		// Router
		//

		if ( $routing_rule )
		{
			self::setHookStage( Charcoal_EnumCoreHookStage::BEFORE_ROUTER );

			// get routers list from profile
			$router_names = $profile->getArray( 'ROUTERS' );

			// register routers
			if ( $router_names ){
				foreach( $router_names as $router_name ){
					if ( strlen($router_name) === 0 )    continue;

					$router = $sandbox->createObject( $router_name, 'router', array(), 'Charcoal_IRouter' );

					$res = $router->route( $request, $routing_rule );
					if ( $res->isTrue() ){
						self::$proc_path = $request->getProcedurePath();
						log_debug( "debug,system","routed: proc_path=[" . self::$proc_path . "]", 'framework' );
						break;
					}
				}
			}

			self::setHookStage( Charcoal_EnumCoreHookStage::AFTER_ROUTER );
		}

		//=======================================
		// Procedureの作成
		//

		// プロシージャを作成
		self::setHookStage( Charcoal_EnumCoreHookStage::BEFORE_CREATE_PROCEDURE, self::$proc_path );

		try{
			$procedure = $sandbox->createObject( self::$proc_path, 'procedure', array(), 'Charcoal_IProcedure' );
		}
		catch( Exception $e )
		{
			_catch( $e );

			_throw( new Charcoal_ProcedureNotFoundException( self::$proc_path, $e ) );
		}

		self::setHookStage( Charcoal_EnumCoreHookStage::AFTER_CREATE_PROCEDURE, self::$proc_path );

		// procedure forwarding
		self::setHookStage( Charcoal_EnumCoreHookStage::BEFORE_PROCEDURE_FORWARD );
		if ( $procedure->hasForwardTarget() ){
			// get forward target path
			$object_path = $procedure->getForwardTarget();
//			log_debug( "debug,system","procedure forward target:" . $object_path, 'framework' );

			self::setHookStage( Charcoal_EnumCoreHookStage::PRE_PROCEDURE_FORWARD, $object_path );

			// create target procedure
			$procedure = $sandbox->createObject( $object_path->toString(), 'procedure', 'Charcoal_IProcedure' );
//			log_debug( "debug,system","forward procedure created:" . $procedure, 'framework' );

			self::setHookStage( Charcoal_EnumCoreHookStage::POST_PROCEDURE_FORWARD, $object_path );
		}
		self::setHookStage( Charcoal_EnumCoreHookStage::AFTER_PROCEDURE_FORWARD );

		//=======================================
		// create response object
		//

		$response = $sandbox->createObject( CHARCOAL_RUNMODE, 'response', array(), 'Charcoal_IResponse' );

		//=======================================
		// ブートストラップ完了
		//

		self::setHookStage( Charcoal_EnumCoreHookStage::END_OF_BOOTSTRAP );

		//=======================================
		// Procedureの実行
		//

		self::setHookStage( Charcoal_EnumCoreHookStage::BEFORE_EXECUTE_PROCEDURES );

		// プロシージャの実行
		while( $procedure )
		{
			$path = $procedure->getObjectPath()->getVirtualPath();

			self::setHookStage( Charcoal_EnumCoreHookStage::PRE_EXECUTE_PROCEDURE, $path );
			$procedure->execute( $request, $response, $session );
			self::setHookStage( Charcoal_EnumCoreHookStage::POST_EXECUTE_PROCEDURE, $path );

			// プロシージャをスタックから取得
			$procedure = self::popProcedure();
		}

		self::setHookStage( Charcoal_EnumCoreHookStage::AFTER_EXECUTE_PROCEDURES );

		//=======================================
		// 終了処理
		//

		self::setHookStage( Charcoal_EnumCoreHookStage::START_OF_SHUTDOWN );
		self::setHookStage( Charcoal_EnumCoreHookStage::BEFORE_SAVE_SESSION );

		$response->terminate();

		// セッション情報の保存
		if ( $use_session->isTrue() && $session )
		{
			// セッションを保存
			$session->save();
			$session->close();
		}

		self::setHookStage( Charcoal_EnumCoreHookStage::AFTER_SAVE_SESSION );

		//=======================================
		// コンテナの破棄
		//

		self::setHookStage( Charcoal_EnumCoreHookStage::BEFORE_DESTROY_CONTAINER );

		$sandbox->getContainer()->terminate();

		self::setHookStage( Charcoal_EnumCoreHookStage::AFTER_DESTROY_CONTAINER );
		self::setHookStage( Charcoal_EnumCoreHookStage::END_OF_SHUTDOWN );

	}

	/**
	 *	フレームワークを起動
	 */
	public static function run( $debug = NULL, $sandbox = NULL )
	{
		Charcoal_Benchmark::start();

//		Charcoal_ParamTrait::checkBoolean( 1, $debug, TRUE );
//		Charcoal_ParamTrait::checkSandbox( 2, $sandbox, TRUE );

		if ( $sandbox === NULL ){
			$sandbox = new Charcoal_Sandbox( CHARCOAL_PROFILE, $debug );
		}

		try{
			try{
				ob_start();
				self::_run( $sandbox );
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
		catch( Exception $e )
		{
			_catch( $e );

			// restore error handlers for avoiding infinite loop
			restore_error_handler();
			restore_exception_handler();

			$ret = self::handleException( $e );

			// display debugtrace
			if ( $ret === NULL || $ret === FALSE || ($ret instanceof Charcoal_Boolean) && $ret->isFalse() || $sandbox->isDebug() ){
				self::renderExceptionFinally( $e );
			}

			self::$loggers->flush();
		}

		// finally process
		$score = Charcoal_Benchmark::stop();
		log_debug( 'system, debug', "total framework process time: [$score] msec" );
 

//Charcoal_Object::dump();

		self::$loggers->terminate();
	}


	/**
	 *	Render not handled exception
	 */
	public static function renderExceptionFinally( $e )
	{
		// Render exception
		$rendered = self::$debugtrace_renderers->render( $e );

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

