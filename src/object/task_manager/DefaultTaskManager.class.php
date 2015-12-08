<?php
/**
* simple task manager
*
* PHP version 5
*
* @package    objects.task_managers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_DefaultTaskManager extends Charcoal_AbstractTaskManager
{
    const TAG = 'charcoal.object.default_task_manager';

    /** @var Charcoal_Itask[] */
    private $tasks;

    /** @var Charcoal_EventQueue */
    private $queue;

    /**
     * Initialize instance
     *
     * @param Charcoal_Config $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );

        $this->max_event_loop     = ui( $config->getInteger( 'max_event_loop', 1000 ) );

        $this->tasks  = new Charcoal_Vector();
        $this->queue  = new Charcoal_EventQueue();
    }

    /**
     * refister task
     *
     * @param Charcoal_String|string $key
     * @param Charcoal_ITask $task
     */
    public function registerTask( $key, $task )
    {
        $key = us( $key );
        if ( isset($this->tasks[$key]) ){
            log_warning( "system,event", "task[$key] is already registered." );
            return;
        }

        // save task in the map
        $this->tasks[$key] = $task;

        log_debug( 'system,event', "registered task[$task] as [$key]" );
    }

    /**
     * unregister task
     *
     * @param Charcoal_String|string $key
     */
    public function unregisterTask( $key )
    {
        $key = us( $key );

        // remove task from the map
        unset( $this->tasks[$key] );
    }

    /**
     * test if task is registered
     *
     * @param Charcoal_String|string $key
     *
     * @return boolean          TRUE if a task is registered, otherwise FALSE
     */
    public function isTaskRegistered( $key )
    {
        $key = us( $key );

        return isset($this->tasks[$key]);
    }

    /**
     * get task
     *
     * @param Charcoal_String|string $key
     *
     * @return Charcoal_ITask
     *
     * @throws Charcoal_TaskNotFoundException
     */
    public function getTask( $key )
    {
        $key = us( $key );

        if ( isset($this->tasks[$key]) ){
            return $this->tasks[$key];
        }

        throw new Charcoal_TaskNotFoundException( $key );
    }

    /*
     * Get event queue
     *
     * @return Charcoal_IEventQueue       event queue object
     */
    public function getEventQueue()
    {
        return $this->queue;
    }

    /**
     *   save statefull task
     *
     * @param Charcoal_Session $session
     */
    public function saveStatefulTasks( $session )
    {
//print "saveStatefulTasks<br>";
        foreach( $this->tasks as $task ){
            if ( $task instanceof Charcoal_IStateful ){
                /** @var Charcoal_ITask|Charcoal_IStateful $task */
                $namespace = $task->getNameSpace();
                $data_id = !empty($namespace) ? "task://{$namespace}/" . $task->getObjectPath() : "task://" . $task->getObjectPath();
                $session->set( $data_id, $task->serializeContents() );
            }
        }
    }

    /**
     *   restore stateful task
     *
     * @param Charcoal_Session $session
     */
    public function restoreStatefulTasks( $session )
    {
//print "restoreStatefulTasks<br>";
        foreach( $this->tasks as $task ){
            if ( $task instanceof Charcoal_IStateful ){
                /** @var Charcoal_ITask|Charcoal_IStateful $task */
                $namespace = $task->getNameSpace();
                $data_id = !empty($namespace) ? "task://{$namespace}/" . $task->getObjectPath() : "task://" . $task->getObjectPath();
                if ( isset($_SESSION[ $data_id ]) ){
                    $data = $_SESSION[ $data_id ];
                    $task->deserializeContents( unserialize($data) );
                }
                else{
                    // デシリアライズされなかった場合は初期化する
                    $task->initContents();
                }
            }
        }
    }

    /*
     *  add an event to task manager
     *
     * @param Charcoal_IEvent $event
     */
    public function pushEvent( $event )
    {
        // add an event to event queue
        $this->queue->enqueue( $event );
        log_debug( 'system,event', "event[$event] was enqueued." );
    }

    /**
     *   process events
     *
     * @param Charcoal_IEventContext $context
     *
     * @return int
     *
     * @throws Charcoal_BusinessException|Charcoal_RuntimeException
     */
    public function processEvents( $context )
    {
        $debug = $this->getSandbox()->isDebug() || $context->getProcedure()->isDebugMode();

        if ( $debug ) log_debug( 'system,event', "processEvents start." );

//        $procedure = $context->getProcedure();
//        $request   = $context->getRequest();
//        $sequence  = $context->getSequence();
//        $response  = $context->getResponse();

        $max_event_loop = $this->max_event_loop;

        $exit_code = 0;

        try{
            $queue = $this->queue;

            $timer_all = Charcoal_Benchmark::start();

            $loop_id = 0;
            while( !$queue->isEmpty() )
            {
                if ( $debug ) log_debug( 'system,event', "event queue(" . count($queue) . "): $queue");

                // increment loop counter
                $loop_id ++;

                // イベント一覧を優先度でソートする
                $queue->sortByPriority();

                /** @var Charcoal_IEvent $event */
                $event      = $queue->dequeue();

                /** @var string $event_name */
                $event_name = $event->getObjectName();

                /** @var Charcoal_ObjectPath $event_id */
                $event_id   = $event->getObjectPath();

                $delete_event = FALSE;

                $context->setEvent( $event );

                // if this event loop exceeds [max_event_loop], thro exception
                if ( $loop_id > $max_event_loop ){
                    log_warning( "system,event", "[loop:$loop_id/$event_name] aborting by overflow maximum loop count[$max_event_loop].", "task_manager" );
                    log_warning( "system,event", "[loop:$loop_id/$event_name] event queue=[$queue].", "task_manager" );
                    _throw( new Charcoal_EventLoopCounterOverflowException( $max_event_loop ) );
                }

                if ( $debug ) log_debug( 'system,event', "[loop:$loop_id/$event_name] event loop start.");

                // タスク一覧を優先度でソートする
                $key_priority = array();
                foreach ( $this->tasks as $key => $task ){
                    $key_priority[$key] = ui( $task->getPriority() );
                }
                $a_task_list = uv($this->tasks);
                array_multisort( $key_priority,SORT_DESC, $a_task_list );
                $this->tasks = v($a_task_list);

                // task list to remove on end of this loop
                $remove_tasks = NULL;

                // すべてのタスクにイベントをディスパッチする
                if ( $debug ) log_debug( 'system,event', "[loop:$loop_id/$event_name] task list: [$this->tasks]" );
                foreach( $this->tasks as $task )
                {
                    $task_name = $task->getObjectName();
                    $task_id   = $task->getObjectPath();
                    if ( $debug ) log_debug( 'system,event', "[loop:$loop_id/$event_name/$task_name] event[$event_name] is dispatching to task[$task_name].");

                    // イベントフィルタ
                    $process = FALSE;
                    $event_filters = $task->getEventFilters();
                    if ( $debug ) log_debug( 'system,event', "[loop:$loop_id/$event_name/$task_name] task event filter: " . $event_filters );
                    foreach( $event_filters as $filter ){
                        if ( $event_id->getObjectPathString() == us($filter) ){
                            $process = TRUE;
                            break;
                        }
                    }

                    if ( !$process ){
                        if ( $debug ) log_debug( 'system,event', "[loop:$loop_id/$event_name/$task_name] event[$event_name] is NOT found in task's event filters: [$event_filters]. Passing this task.");
                        continue;
                    }
                    if ( $debug ) log_debug( 'system,event', "[loop:$loop_id/$event_name/$task_name] event[$event_name] is found in task's event filters: [$event_filters].");

                    // task timer start
                    $timer_task = Charcoal_Benchmark::start();

                    $result = NULL;
                    try{
                        $result = $task->processEvent( $context );
                        if ( $debug ) log_debug( 'system,event', "[loop:$loop_id/$event_name/$task_name] returned from processEvent with result:" . print_r($result,true) );
                    }
                    catch( Charcoal_BusinessException $e )
                    {
                        // just handle the exception
                        $exception_handled = $task->handleException( $e, $context );
                        if ( b($exception_handled)->isFalse() ){
                            // just re-throw the exception, if the exception was not handled by the task
                            throw( $e );
                        }
                    }
                    catch( Charcoal_RuntimeException $e )
                    {
                        // write log and handle the exception
                        _catch( $e );
                        $exception_handled = $task->handleException( $e, $context );
                        if ( b($exception_handled)->isFalse() ){
                            // write log and re-throw the exception, if the exception was not handled by the task
                            _throw( $e );
                        }
                    }

                    // result value handling
                    $result_str = NULL;
                    if ( $result === NULL ){
                        $result_str = 'NULL';
                    }
                    elseif ( $result === FALSE || ($result instanceof Charcoal_Boolean) && $result->isFalse() ){
                        $result_str = 'FALSE';
                    }
                    elseif ( $result === TRUE || ($result instanceof Charcoal_Boolean) && $result->isTrue() ){
                        $result_str = 'TRUE';
                    }
                    else{
                        $msg = "processEvent() must return a [boolean] value. but returned:" . print_r($result,true);
                        log_error( 'system,event,error', $msg, self::TAG );
                        _throw( new Charcoal_ProcessEventAtTaskException( $event, $task, $result, $msg ) );
                    }

                    // task timer stop
                    $elapse = Charcoal_Benchmark::stop( $timer_task );
                    log_debug( 'system,event', "[loop:$loop_id/$event_name/$task_name] event was processed by task. result=[$result_str] time=[$elapse]msec." );

                    // ポストアクション
                    $post_actions = $task->getPostActions();

                    if ( $debug ) log_debug( 'system,event', "[loop:$loop_id/$event_name/$task_name] task post actions: $post_actions" );
                    if ( $post_actions )
                    {
                        foreach( $post_actions as $key => $action )
                        {
                            $target = NULL;
                            $action = us($action);

                            if ( strpos(":",$action) !== FALSE ){
                                list( $action, $target ) = explode( ":", trim($action) );
                                if ( $debug ) log_debug( 'system,event', "[loop:$loop_id/$event_name/$task_name] post action[$action] with target[$target].");
                            }
                            else{
                                $action = trim($action);
                                if ( $debug ) log_debug( 'system,event', "[loop:$loop_id/$event_name/$task_name] post action[$action].");
                            }
                            switch( $action ){
                            case "remove_task";
                                // タスク実行リストからタスクを削除
                                if ( !$target ){
                                    $target = $task_id;
                                }
                                if ( $target == $task_id ){
                                    if ( $debug ) log_debug( 'system,event', "[loop:$loop_id/$event_name/$task_name] task[$target] is marked to remove." );
                                    $remove_tasks[] = $task_id;
                                }
                                break;
                            case "remove_event":
                                // イベントを削除
                                if ( !$target ){
                                    $target = $event_id;
                                }
                                if ( $target == $event_id ){
                                    if ( $debug ) log_debug( 'system,event', "[loop:$loop_id/$event_name/$task_name] event[$target] is marked to remove.");
                                    $delete_event |= TRUE;
                                }
                                break;
                            case "continue_event":
                                // イベントをキューに再投入
                                break;
                            }
                        }
                    }
                    else{
                        if ( $debug ) log_debug( 'system,event', "[loop:$loop_id/$event_name/$task_name] no post action is  defined for event.");
                    }

                    if ( $debug ) log_debug( 'system,event', "[loop:$loop_id/$event_name] task loop end.");

                } // task loop end

                // remove tasks
                if ( $remove_tasks ){
                    foreach( $remove_tasks as $task_id ){
                        unset($this->tasks["$task_id"]);
                        if ( $debug ) log_debug( 'system,event', "[loop:$loop_id/$event_name] removed task: $task_id" );
                    }
                    if ( $debug ) log_debug( 'system,event', "[loop:$loop_id/$event_name] next task list: [$this->tasks]" );
                }

                if ( !$delete_event ){
                    // push back the event into our event queue
                    $this->pushEvent( $event );
                }
                else{
                    if ( $debug ) log_debug( 'system,event', "[loop:$loop_id/$event_name] event[$event] is removed." );
                }

                if ( $debug ) log_debug( 'system,event', "[loop:$loop_id/$event_name] event loop end.");

            } // event loop end

            if ( $queue->isEmpty() ){
                if ( $debug ) log_debug( 'system,event', "event queue is empty.");
                $exit_code = Charcoal_Event::EXIT_CODE_OK;
            }

            // ログ
            $elapse = Charcoal_Benchmark::stop( $timer_all );
            if ( $debug ) log_debug( 'system,event', "event loop end. time=[$elapse]msec.");
        }
        catch( Charcoal_RuntimeException $e ){
            _catch( $e );
            if ( $debug ) log_debug( 'system,event,debug', "an exception occured while processing event." );
            _throw( new Charcoal_ProcessEventAtTaskManagerException( $e ) );
        }

        if ( $debug ) log_debug( 'system,event', "processEvents end: exit_code=" . print_r($exit_code,true) );

        return $exit_code;
    }



}


