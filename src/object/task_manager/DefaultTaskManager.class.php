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
	private $tasks;
	private $queue;

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$this->max_event_loop     = $config->getInteger( 'max_event_loop', i(1000) )->unbox();

		$this->tasks  = new Charcoal_Vector();
		$this->queue  = new Charcoal_EventQueue();
	}

	/*
	 * タスクを登録する
	 */
	public function registerTask( Charcoal_String $key, Charcoal_ITask $task )
	{

		$key = us( $key );
		if ( isset($this->tasks[$key]) ){
			log_warning( "system,event", "タスク[$key]は登録済みです。" );
			return;
		}

		// タスクを保存
		$this->tasks[$key] = $task;

		log_debug( 'system,event', "registered task[$task] as [$key]" );
	}

	/*
	 * タスクを登録を解除する
	 */
	public function unregisterTask( Charcoal_String $key )
	{
		$key = $key;

		// タスクを削除
		unset( $this->tasks[$key] );
	}

	/*
	 * タスクが登録されているか
	 */
	public function isTaskRegistered( Charcoal_String $key )
	{
		$key = $key;

		return isset($this->tasks[$key]);
	}

	/*
	 * タスクを取得する
	 */
	public function getTask( Charcoal_String $task_name )
	{
		$task_name = $task_name;

		if ( isset($this->tasks[$task_name]) ){
			return $this->tasks[$task_name];
		}

		throw new Charcoal_TaskNotFoundException( $task_name );
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
	 *   ステートフルタスクの保存を行う
	 *
	 */
	public function saveStatefulTasks( Charcoal_Session $session )
	{
//print "saveStatefulTasks<br>";
		foreach( $this->tasks as $task ){
			if ( $task instanceof Charcoal_IStateful ){
				$namespace = $task->getNameSpace();
				$data_id = !$namespace->isEmpty() ? "task://" . us($namespace) . "/" . $task->getObjectPath() : "task://" . $task->getObjectPath();
				$session->set( s($data_id), $task->serializeContents() );
			}
		}
	}

	/**
	 *   ステートフルタスクの復帰を行う
	 *
	 */
	public function restoreStatefulTasks( Charcoal_Session $session )
	{
//print "restoreStatefulTasks<br>";
		foreach( $this->tasks as $task ){
			if ( $task instanceof Charcoal_IStateful ){
				$namespace = $task->getNameSpace();
				$data_id = !$namespace->isEmpty() ? "task://" . us($namespace) . "/" . $task->getObjectPath() : "task://" . $task->getObjectPath();
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
	 */
	public function pushEvent( Charcoal_IEvent $event )
	{
		// add an event to event queue
		$this->queue->enqueue( $event );
		log_debug( 'system,event', "event[" . $event . "] was enqueued." );
	}

	/**
	 *   イベント処理を行う
	 *
	 */
	public function processEvents( Charcoal_IEventContext $context  )
	{
		$debug = $this->getSandbox()->isDebug() || $context->getProcedure()->isDebugMode();

		if ( $debug ) log_debug( 'system,event', "processEvents start." );

		$procedure = $context->getProcedure();
		$request   = $context->getRequest();
		$sequence  = $context->getSequence();
		$response  = $context->getResponse();

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

				// イベントを取得
				$event      = $queue->dequeue();
				$event_name = $event->getObjectName();
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
					}
					catch( Charcoal_BusinessException $e )
					{
						// just handle the exception
						$task->handleException( $e, $context );
					}
					catch( Charcoal_RuntimeException $e )
					{
						// write log and handle the exception
						_catch( $e );
						$task->handleException( $e, $context );
					}

					// result value handling
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
						_throw( new Charcoal_ProcessEventAtTaskException( $event, $task, $result, "processEvent() must return a [boolean] value." ) );
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


