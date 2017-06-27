<?php
/**
* Layoutable HTTP procedure
*
* PHP version 5
*
* @package    objects.procedures
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_HttpProcedure extends Charcoal_AbstractProcedure
{
    private $use_session;
    private $layout_manager;

    /**
     * Initialize instance
     *
     * @param array $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );
        
        $config = new Charcoal_HashMap($config);

        $this->use_session         = $config->getBoolean( 'use_session', TRUE );
        $layout_manager            = $config->getString( 'layout_manager' );

        $this->setLayoutManager( $layout_manager );

        if ( $this->getSandbox()->isDebug() )
        {
            log_info( "system,config", "procedure", "use_session:" . $this->use_session );
            log_info( "system,config", "procedure", "layout_manager:" . $this->layout_manager );
        }
    }

    /**
     * レイアウトマネージャを取得
     *
     * @return Charcoal_ILayoutManager
     */
    public function getLayoutManager()
    {
        return $this->layout_manager;
    }

    /*
     * レイアウトマネージャを設定
     */
    public function setLayoutManager( $layout_manager )
    {
        if ( $layout_manager === NULL  || $layout_manager instanceof Charcoal_ILayoutManager )
        {
            $this->layout_manager = $layout_manager;
        }
        elseif ( is_string($layout_manager) || $layout_manager instanceof Charcoal_String )
        {
            $layout_manager = s($layout_manager);
            if ( $layout_manager->isEmpty() ){
                $this->layout_manager = NULL;
            }
            else{
                try{
                    $this->layout_manager = $this->getSandbox()->createObject( $layout_manager, 'layout_manager' );
                }
                catch( Exception $e ){
                    _catch( $e );

                    _throw( new Charcoal_LayoutManagerCreationException( $layout_manager, $e ) );
                }
            }
        }
    }

    /**
     * Execute procedure
     *
     * @param Charcoal_IRequest $request      request object
     * @param Charcoal_IResponse $response    response object
     * @param Charcoal_Session $session       session object
     */
    public function execute( $request, $response, $session = NULL )
    {
        $timer_handle = Charcoal_Benchmark::start();

        $proc_path = $this->getObjectPath();

        //=======================================
        // タスクマネージャの作成
        //

        // タスクマネージャを作成
        /* @var Charcoal_ITaskManager $task_manager */
        $task_manager_name = $this->task_manager;
        $task_manager = $this->getSandbox()->createObject( $task_manager_name, 'task_manager' );

        //=======================================
        // modules以下にクラスファイルがあればロードする
        //

        Charcoal_ModuleLoader::loadModule( $this->getSandbox(), $proc_path, $task_manager );

        //=======================================
        // 追加モジュールのロード
        //

        if ( $this->modules ) {
            foreach( $this->modules as $module_name ) {
                if ( strlen($module_name) === 0 )    continue;
                // load module
                Charcoal_ModuleLoader::loadModule( $this->getSandbox(), $module_name, $task_manager );
            }
        }

        //=======================================
        // ステートフルタスクの復帰
        //

        $use_session = b( $this->getSandbox()->getProfile()->getBoolean( 'USE_SESSION' ) );

        if ( $use_session->isTrue() ){
            $task_manager->restoreStatefulTasks( $session );
        }

        //=======================================
        // create system event(request event)
        //

        // create request event
        /* @var Charcoal_RequestEvent $event */
        $event = $this->getSandbox()->createEvent( 'request', array($request) );
        $task_manager->pushEvent( $event );

        //=======================================
        // create user events
        //
        if ( $this->events )
        {
            foreach( $this->events as $event_name )
            {
                if ( strlen($event_name) === 0 )    continue;

                $event = $this->getSandbox()->createEvent( $event_name );
                $task_manager->pushEvent( $event );
            }
        }

        //=======================================
        // イベント処理
        //

        $context = new Charcoal_EventContext( $this->getSandbox(), $this, $request, $response, $event, $session, $task_manager );

        $exit_code = $task_manager->processEvents( $context );
        if ( !is_int($exit_code) && !($exit_code instanceof Charcoal_Integer) ){
            _throw( new Charcoal_BadExitCodeException( $exit_code ) );
        }

        //=======================================
        // 終了処理
        //

        // セッション情報の保存
        if ( $use_session->isTrue() )
        {
            // ステートフルタスクの保存
            $task_manager->saveStatefulTasks( $session );
        }

        $score = Charcoal_Benchmark::stop( $timer_handle );
        log_debug( 'system, debug', "procedure execute method end: [$score] msec" );
    }
}

