<?php
/**
 * CharcoalPHP ver 2.9.6
 *
 * task-oriented web framework.
 *
 * PHP version 5.2 
 *
 * Copyright 2008 stk2k in japan
 * Technical  :  http://charcoalphp.org/
 * Licensed under The MIT License License
 *
 * @copyright        2008 stk2k, sazysoft
 * @link            http://charcoalphp.org/
 * @version            2.9.6
 * @lastmodified    2013-04-26
 * @license            http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
*  フレームワークのメインクラス
*
* PHP version 5
*
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_Framework
{
    /** @var Charcoal_Stack */
    static $proc_stack;

    /** @var string */
    static $proc_path;

    /** @var Charcoal_IRequest */
    static $request;

    /** @var int */
    static $hook_stage;

    /** @var string[] */
    static $loaded_files;

    /** @var Charcoal_ExceptionHandlerList */
    static $exception_handlers;

    /** @var Charcoal_DebugTraceRendererList */
    static $debugtrace_renderers;

    /** @var Charcoal_LoggerList */
    static $loggers;

    /** @var Charcoal_CacheDriverList */
    static $cache_drivers;
    
    /**
     *    load source file
     *
     * @param Charcoal_String|string $path         module file path to load
     */
    public static function loadSourceFile( $path )
    {
//        Charcoal_ParamTrait::validateString( 1, $path );
/*
        if ( !is_file($path) ){
            _throw( new Charcoal_FileNotFoundException( $path ) );
        }

        if ( !is_readable($path) ){
            _throw( new Charcoal_FileNotReadableException( $path ) );
        }
*/

        /** @noinspection PhpIncludeInspection */
        include( $path );
        self::$loaded_files[] = $path;
        log_info( "system,debug,include","loaded source file: [$path]", 'framework' );
    }

    /**
     *    get loaded source file
     */
    public static function getLoadedSourceFiles()
    {
        return self::$loaded_files ? self::$loaded_files : array();
    }

    /**
     *    プロシージャスタックを取得
     */
    public static function getProcedureStack()
    {
        return self::$proc_stack;
    }

    /**
     *    push procedure
     *
     * @param Charcoal_IProcedure $procedure
     */
    public static function pushProcedure( $procedure )
    {
        if ( !self::$proc_stack ){
            self::$proc_stack = new Charcoal_Stack();
        }
        self::$proc_stack->push( $procedure );

//        log_debug( 'system, debug', "pushed procedure[" . $procedure->getObjectPath() . "]." );
    }

    /**
     *    pop procedure
     *
     * @return Charcoal_IProcedure|NULL
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

    /**
     *    get version info about framework
     *    
     *    @return Charcoal_FrameworkVersion              version info about framework
     */
    public static function getVersion()
    {
        return new Charcoal_FrameworkVersion();
    }
    
    /**
     *    get version info about framework
     *
     *    @return Charcoal_FrameworkVersion              version info about framework
     */
    public static function getShortVersion()
    {
        return new Charcoal_FrameworkVersion( Charcoal_FrameworkVersion::VERSION_PART_SHORT );
    }
    
    /**
     *    get version info about framework
     *
     *    @return Charcoal_FrameworkVersion              version info about framework
     */
    public static function getLongVersion()
    {
        return new Charcoal_FrameworkVersion( Charcoal_FrameworkVersion::VERSION_PART_LONG );
    }

    /*
     *    リクエストを取得
     */
    public static function getRequest()
    {
        return self::$request;
    }

    /*
     *    リクエストパスを取得
     */
    public static function getRequestPath()
    {
        return self::$proc_path;
    }

    /*
     * write one message
     */
    public static function writeLog( $target, $message,  $tag = NULL )
    {
        if ( self::$loggers ){
            self::$loggers->writeLog( $target, $message,  $tag );
        }
        else{
            echo $message . eol();
        }
    }

    /**
     * set log level
     * 
     * @param Charcoal_String|string  $log_level     new log level
     * 
     * @return Charcoal_String|string        old log level
     */
    public static function setLogLevel( $log_level )
    {
        return self::$loggers ? self::$loggers->setLogLevel( $log_level ) : FALSE;
    }

    /**
     * Get non-typed data which is associated with a string key
     *
     * @param Charcoal_String $key         The key of the item to retrieve.
     *
     * @return mixed                       cache data
     */
    public static function getCache( $key )
    {
        return self::$cache_drivers->getCache( $key );
    }

    /**
     * Save a value to cache
     *
     * @param Charcoal_String $key         The key under which to store the value.
     * @param Charcoal_Object $value       value to store
     * @param Charcoal_Integer $duration   specify expiration span which the cache will be removed.
     */
    public static function setCache( $key, $value, $duration = NULL )
    {
        self::$cache_drivers->setCache( $key, $value, $duration );
    }

    /**
     * Remove a cache data
     *
     * @param Charcoal_String $key         The key of the item to remove. Shell wildcards are accepted.
     */
    public static function deleteCache( $key )
    {
        self::$cache_drivers->deleteCache( $key );
    }

    /**
     *    execute framework main process
     *
     * @param Charcoal_Sandbox $sandbox
     */
    private static function _run( $sandbox )
    {
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
        // create cache driver list
        self::$cache_drivers = new Charcoal_CacheDriverList( $sandbox );
    
        //==================================================================
        // core hook event stream
    
        $event_stream = $sandbox->getCoreHookEventStream();
    
        //==================================================================
        // load sandbox
    
        $event_stream->push( 'profile.begin', null, true );

        $profile = NULL;
        try{
            $profile = $sandbox->load();
        }
        catch( Exception $ex ){
            _catch( $ex );
            _throw( $ex );
        }
    
        $event_stream->push( 'profile.end', null, true );
    
        //=======================================
        // フレームワーク初期化処理
        //
    
        // タイムアウトを指定
        $timeout = $profile->getInteger( 'SCRIPT_TIMEOUT', ini_get("max_execution_time") );
        set_time_limit( ui($timeout) );
    
        $event_stream->push( 'bootstrap.timeout', null, true );

        //=======================================
        // クラスローダの登録
        //
    
        $event_stream->push( 'class_loader.begin', null, true );
        
        $framework_class_loader = $sandbox->createClassLoader( 'framework' );
        $event_stream->push( 'class_loader.create_framework', null, true );
    
        Charcoal_ClassLoader::addClassLoader( $framework_class_loader );
        $event_stream->push( 'class_loader.add_framework', null, true );

        try{
            $class_loaders = $profile->getArray( 'CLASS_LOADERS' );
            if ( $class_loaders ){
                foreach( $class_loaders as $loader_name ) {

                    if ( strlen($loader_name) === 0 )    continue;

                    $loader = $sandbox->createClassLoader( $loader_name );
                    $event_stream->push( 'class_loader.create_user', null, true );
                    
                    Charcoal_ClassLoader::addClassLoader( $loader );
                    $event_stream->push( 'class_loader.add_user', null, true );
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
    
        $event_stream->push( 'class_loader.end', null, true );
    
        //=======================================
        // create request object

        /** @var Charcoal_IRequest $request */
        $request = $sandbox->createObject( CHARCOAL_RUNMODE, 'request', array(), array(), 'Charcoal_IRequest' );
        self::$request = $request;
    
        $event_stream->push( 'request.created', null, true );
    
        self::$proc_path = us($request->getProcedurePath());

        // if procedure path is not specified in url, forward the procedure to DEFAULT_PROCPATH in profile.ini
        if ( strlen(self::$proc_path) === 0 ){
            self::$proc_path = us($profile->getString( 'DEFAULT_PROCPATH' ));
        }
    
        $event_stream->push( 'request.proc_path', self::$proc_path, true );
    
        $sandbox->getEnvironment()->set( '%REQUEST_PATH%', self::$proc_path );
    
        
        //=======================================
        // create loggers
    
        $event_stream->push( 'logger.begin', null, true );
    
        self::$loggers->init();
    
        $event_stream->push( 'logger.end', null, true );
    
    
        //=======================================
        // 外部ライブラリの使用
        //
    
        $event_stream->push( 'ext_lib.begin', null, true );
    
        $use_extlib = b( $profile->getBoolean( 'USE_EXTLIB', FALSE ) );

        if ( $use_extlib->isTrue() ){
            $lib_dirs = $profile->getArray( 'EXTLIB_DIR', array(), TRUE );
            if ( $lib_dirs ){
                foreach( $lib_dirs as $dir ){
                    if ( strlen($dir) === 0 )    continue;

                    if ( !file_exists($dir) ){
                        _throw( new Charcoal_ProfileConfigException( 'EXTLIB_DIR', "directory [$dir] does not exists") );
                    }
                    if ( !is_dir($dir) ){
                        _throw( new Charcoal_ProfileConfigException( 'EXTLIB_DIR', "[$dir] is not directory") );
                    }
                    ini_set( 'include_path', ini_get('include_path') . PATH_SEPARATOR . $dir );
                    
                    $event_stream->push( 'ext_lib.add', $dir, true );
                }
            }
        }
    
        $event_stream->push( 'ext_lib.end', null, true );
    
        //=======================================
        // セッションハンドラの作成
        //
    
        $event_stream->push( 'session_handler.begin', null, true );

        $use_session = b( $profile->getBoolean( 'USE_SESSION', FALSE ) );

        if ( $use_session->isTrue() )
        {
            // セッションハンドラ名の取得
            $session_handler_name = us($profile->getString('SESSION_HANDLER_NAME','default'));

            if ( !empty($session_handler_name) )
            {
                // セッションハンドラの作成
                $session_handler = $sandbox->createObject( $session_handler_name, 'session_handler', array(), array(), 'Charcoal_ISessionHandler' );
    
                $event_stream->push( 'session_handler.created', $session_handler_name, true );

                session_set_save_handler(
                            array( $session_handler, 'open' ), 
                            array( $session_handler, 'close' ), 
                            array( $session_handler, 'read' ), 
                            array( $session_handler, 'write' ), 
                            array( $session_handler, 'destroy' ), 
                            array( $session_handler, 'gc' )
                    );
                $event_stream->push( 'session_handler.set', null, true );
            }
        }
    
        $event_stream->push( 'session_handler.end', null, true );

        //=======================================
        // Create Session
        //
    
        $event_stream->push( 'session.begin', null, true );

        $session = NULL;
        if ( $use_session->isTrue() )
        {
            // create session
            $session = new Charcoal_Session();

            // start session
            $session->start();
    
            $event_stream->push( 'session.start', null, true );

            // restore session
            $session->restore();
    
            $event_stream->push( 'session.restore', null, true );
        }
    
        $event_stream->push( 'session.end', null, true );

        //=======================================
        // Routing Rule
        //
    
        $event_stream->push( 'routing_rule.begin', null, true );

        // get routers list from profile
        $routing_rule_name = $profile->getString( 'ROUTING_RULE' );
        $routing_rule_name = us($routing_rule_name);

        // register routers
        $routing_rule = NULL;
        if ( !empty($routing_rule_name) ) {
            /** @var Charcoal_IRoutingRule $routing_rule */
            $routing_rule = $sandbox->createObject( $routing_rule_name, 'routing_rule', array(), array(), 'Charcoal_IRoutingRule' );
    
            $event_stream->push( 'routing_rule.created', $routing_rule_name, true );
        }
    
        $event_stream->push( 'routing_rule.end', null, true );

        //=======================================
        // Router
        //

        if ( $routing_rule )
        {
            $event_stream->push( 'router.begin', null, true );
            
            // get routers list from profile
            $router_names = $profile->getArray( 'ROUTERS' );

            // register routers
            if ( $router_names ){
                foreach( $router_names as $router_name ){
                    if ( strlen($router_name) === 0 )    continue;

                    /** @var Charcoal_IRouter $router */
                    $router = $sandbox->createObject( $router_name, 'router', array(), array(), 'Charcoal_IRouter' );
    
                    $event_stream->push( 'router.created', $router_name, true );
                    
                    $res = $router->route( $request, $routing_rule );
    
                    $event_stream->push( 'router.route', $res, true );
    
                    if ( is_array($res) ){
                        self::$proc_path = $request->getProcedurePath();
                        $sandbox->getEnvironment()->set( '%REQUEST_PATH%', self::$proc_path );
                        log_debug( "debug,system","routed: proc_path=[" . self::$proc_path . "]", 'framework' );
                        break;
                    }
                }
            }
    
            $event_stream->push( 'router.end', null, true );
        }

        //=======================================
        // create procedure
    
        $event_stream->push( 'procedure.begin', null, true );
        
        $procedure = NULL;
        try{
            /** @var Charcoal_IProcedure $procedure */
            $procedure = $sandbox->createObject( self::$proc_path, 'procedure', array(), array(), 'Charcoal_IProcedure' );
    
            $event_stream->push( 'procedure.created', self::$proc_path, true );
        }
        catch( Exception $e )
        {
            _catch( $e );

            _throw( new Charcoal_ProcedureNotFoundException( self::$proc_path, $e ) );
        }
    
        $event_stream->push( 'procedure.end', null, true );
    
        //=======================================
        // procedure forwarding
    
        $event_stream->push( 'forward_procedure.begin', null, true );
    
        if ( $procedure->hasForwardTarget() ){
            // get forward target path
            $forward_proc_path = $procedure->getForwardTarget();

            // create target procedure
            $procedure = $sandbox->createObject( $forward_proc_path, 'procedure', 'Charcoal_IProcedure' );
    
            $event_stream->push( 'forward_procedure.created', $forward_proc_path, true );
        }

        // override logger settings by the procedure's settings
        self::$loggers->overrideByProcedure( $procedure );
    
        $event_stream->push( 'forward_procedure.end', null, true );
    
        
        //=======================================
        // create response object
    
        /** @var Charcoal_IResponse $response */
        $response = $sandbox->createObject( CHARCOAL_RUNMODE, 'response', array(), array(), 'Charcoal_IResponse' );
    
        $event_stream->push( 'response.created', CHARCOAL_RUNMODE, true );
    
        //=======================================
        // execute procedures
    
        $event_stream->push( 'execute_procedure.begin', null, true );
    
        // プロシージャの実行
        while( $procedure )
        {
            $procedure->execute( $request, $response, $session );
            
            $event_stream->push( 'execute_procedure.executed', $procedure->getObjectPath()->toString(), true );
    
            $procedure = self::popProcedure();
            
            $event_stream->push( 'execute_procedure.poped', $procedure ? $procedure->getObjectPath()->toString() : null, true );
        }
    
        $event_stream->push( 'execute_procedure.end', null, true );
    
        //=======================================
        // framework shutdown process
    
        $event_stream->push( 'shutdown.begin', null, true );
    
        // セッション情報の保存
        if ( $use_session->isTrue() && $session )
        {
            // sve session
            $session->save();
            
            $event_stream->push( 'session.saved', null, true );
    
            $session->close();
            
            $event_stream->push( 'session.closed', null, true );
        }
    
        $sandbox->getContainer()->terminate();
    
        $event_stream->push( 'container.terminated', null, true );

        $event_stream->push( 'shutdown.end', null, true );
    }

    /**
     *    execute framework main code
     *
     * @param Charcoal_Sandbox $sandbox
     */
    public static function run( $sandbox = NULL )
    {
        $th_run = Charcoal_Benchmark::start();
        $mh_run = Charcoal_MemoryBenchmark::start();

        if ( $sandbox === NULL ){
            $sandbox = new Charcoal_Sandbox();
        }

        try{
            try{
                //ob_start();
                self::_run( $sandbox );
                //ob_end_flush();
            }
            catch( Charcoal_ProcedureNotFoundException $ex )
            {
                _catch( $ex );

                switch( CHARCOAL_RUNMODE ){
                // ランモードがhttpの時は404エラー
                case 'http':
                    throw( new Charcoal_HttpStatusException( 404, $ex ) );
                    break;
                // それ以外の場合はリスロー
                default:
                    _throw( $ex );
                    break;
                }
            }
            catch( Exception $ex )
            {
                _catch( $ex );

                switch( CHARCOAL_RUNMODE ){
                // ランモードがhttpの時は500エラー
                case 'http':
                    throw( new Charcoal_HttpStatusException( 500, $ex ) );
                    break;
                // それ以外の場合はリスロー
                default:
                    _throw( $ex );
                    break;
                }
            }
        }
        catch( Charcoal_ProfileLoadingException $e )
        {
            echo 'profile loading failed:' . $e->getMessage();
            exit;
        }
        catch( Exception $e )
        {
            _catch( $e );
    
            // restore error handlers for avoiding infinite loop
            restore_error_handler();
            restore_exception_handler();
    
            $handled = self::handleException( $e );
    
            // dump registry items
            if ( !$handled ){
                self::dumpRegistryItems( $sandbox->getRegistry() );
            }
        }
        /** @noinspection PhpUndefinedClassInspection */
        catch( Throwable $e )
        {
            _catch( $e );
    
            // restore error handlers for avoiding infinite loop
            restore_error_handler();
            restore_exception_handler();
    
            $handled = self::handleException( $e );
    
            // dump registry items
            if ( !$handled ){
                self::dumpRegistryItems( $sandbox->getRegistry() );
            }
        }

        // finally process
        $timer_score = Charcoal_Benchmark::stop( $th_run );
        log_debug( 'system, debug', sprintf("total framework process time: [%0.4f] msec",$timer_score) );

        // memory usage
        list( $usage_1, $usage_2 ) = Charcoal_MemoryBenchmark::stop( $mh_run );
        log_debug( 'system, debug', sprintf("used memory: [%d] bytes / [%d] bytes", $usage_1, $usage_2) );

        // memory peak usage
        $peak_usage_1 = Charcoal_MemoryUtil::convertSize( memory_get_peak_usage(true), Charcoal_EnumMemoryUnit::UNIT_B );
        $peak_usage_2 = Charcoal_MemoryUtil::convertSize( memory_get_peak_usage(false), Charcoal_EnumMemoryUnit::UNIT_B );
        log_debug( 'system, debug', sprintf("peak memory: [%d] bytes / [%d] bytes", $peak_usage_1, $peak_usage_2) );
    
        // termination
        $sandbox->terminate();
    
        // framework termination
        if ( self::$loggers ){
            self::$loggers->terminate();
        }
        self::$loggers = null;
    }
    
    /**
     *  dump registry items
     *
     * @param Charcoal_IRegistry $registry
     *
     */
    private static function dumpRegistryItems($registry)
    {
        $loaded_registry_items = $registry->dumpLoadedItems(TRUE);
    
        switch( CHARCOAL_RUNMODE ){
            case 'http':
                echo "<H1>Loaded Registry Items</H1>";
                echo "<ul>";
                foreach ($loaded_registry_items as $item){
                    echo "<li>$item</li>";
                }
                echo "</ul>";
                break;
            default:
                echo "-----[ Loaded Registry Items ]-----" . PHP_EOL;
                foreach ($loaded_registry_items as $item){
                    echo $item . PHP_EOL;
                }
                echo "-----------------------------------" . PHP_EOL;
                break;
        }
    }
    
    /**
     * execute exception handlers
     *
     * @param Exception $e     exception to handle
     *
     * @return bool
     */
    public static function handleException( $e )
    {
        try{
            $handled = self::$exception_handlers->handleException( $e );
            
            // display debugtrace
            self::$debugtrace_renderers->render( $e );
    
            return $handled;
        }
        catch( Exception $e ){
    
            _catch( $e );
    
            _throw(new Charcoal_ExceptionHandlerException('failed to handle exception:' . $e->getMessage(), $e) );
        }
        return false;
    }

}

