<?php
/**
* セッションなしプローシージャ
*
* PHP version 5
*
* @package    objects.procedures
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_SimpleProcedure extends Charcoal_AbstractProcedure
{
    /**
     * Execute procedure
     *
     * @param Charcoal_IRequest $request      request object
     * @param Charcoal_IResponse $response    response object
     * @param Charcoal_Session $session       session object
     */
    public function execute( $request, $response, $session = NULL )
    {
        $proc_path = $this->getObjectPath();
        $proc_name = $proc_path->toString();

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

        log_info( "system", "プロシージャ[$proc_name]を実行しました。" );
    }

}

