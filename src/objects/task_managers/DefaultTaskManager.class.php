<?php
/**
* simple task manager
*
* PHP version 5
*
* @package    task_managers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
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

		$this->max_event_loop     = $config->getInteger( 'max_event_loop', i(3) )->unbox();

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

		log_info( "system,event", "registered task[$task] as [$key]" );
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
		log_info( "system,event", "event[" . $event . "] was enqueued." );
	}

	/**
	 *   イベント処理を行う
	 *
	 */
	public function processEvents( Charcoal_IEventContext $context  )
	{
		$debug = $this->getSandbox()->isDebug();

		if ( $debug ) log_info( "system,event", "processEvents start." );

		$procedure = $context->getProcedure();
		$request   = $context->getRequest();
		$sequence  = $context->getSequence();
		$response  = $context->getResponse();

		$max_event_loop = $this->max_event_loop;

		$exit_code = 0;

//		try{
			$queue = $this->queue;

			Charcoal_Benchmark::start();

			$loop_id = 0;
			while( !$queue->isEmpty() ){

				// initialize values for this loop
				$abort_after_this_loop = FALSE;

				// increment loop counter
				$loop_id ++;

				// イベント一覧を優先度でソートする
				$queue->sortByPriority();

				// イベントを取得
				$event      = $queue->dequeue();
				$event_name = $event->getObjectName();
				$event_id   = $event->getObjectPath();

				$context->setEvent( $event );

				// if this event loop exceeds [max_event_loop], thro exception
				if ( $loop_id > $max_event_loop ){
					log_warning( "system,event", "[loop:$loop_id/$event_name] aborting by overflow maximum loop count[$max_event_loop].", "task_manager" );
					log_warning( "system,event", "[loop:$loop_id/$event_name] event queue=[$queue].", "task_manager" );
					_throw( new Charcoal_EventLoopCounterOverflowException( $max_event_loop ) );
				}

				if ( $debug ) log_info( "system,event", "[loop:$loop_id/$event_name] event loop start.");

				// タスク一覧を優先度でソートする
				$key_priority = array();
				foreach ( $this->tasks as $key => $task ){
					$key_priority[$key] = ui( $task->getPriority() );
				}
				$a_task_list = uv($this->tasks);
				array_multisort( $key_priority,SORT_DESC, $a_task_list );
				$this->tasks = v($a_task_list);

				// すべてのタスクにイベントをディスパッチする
				if ( $debug ) log_info( "system,event", "[loop:$loop_id/$event_name] task list: [$this->tasks]" );
				foreach( $this->tasks as $task ){

					$task_name = $task->getObjectName();
					$task_id   = $task->getObjectPath();
					if ( $debug ) log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] event[$event_name] is dispatching to task[$task_name].");

					// イベントフィルタ
					$process = FALSE;
					$event_filters = $task->getEventFilters();
					if ( $debug ) log_info( "event", "[loop:$loop_id/$event_name/$task_name] task event filter: " . $event_filters );
					foreach( $event_filters as $filter ){
						if ( $event_id == us($filter) ){
							$process = TRUE;
							break;
						}
					}

					if ( !$process ){
						if ( $debug ) log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] event[$event_name] is NOT found in task's event filters: [$event_filters]. Passing this task.");
						continue;
					}
					if ( $debug ) log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] event[$event_name] is found in task's event filters: [$event_filters].");

					// ガード条件判定
/*
					$process = TRUE;
					$guard_conditions = $task->getGuardConditions();
					log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] task guard conditions are : [$guard_conditions]" );
					if ( $guard_conditions ){
						foreach( $guard_conditions as $tid => $conditions )
						{
							$tid = s($tid);
							if ( $this->isTaskRegistered( $tid ) ){
								$guard_task = $this->getTask( $tid );
								foreach( $conditions as $key => $value ){
									// valueを型名：値に分ける
									$pos = strpos($value,":");
									if ( $pos === FALSE ){
										// 型指定がない場合、デフォルトは文字列
										$value = s($value);
									}
									else{
										$type = substr($value,0,$pos);
										$value = substr($value,$pos+1);
										switch( $type ){
										case "b":	$value = boolval($value);		break;
										case "i":	$value = intval($value);		break;
										case "s":	$value = $value;				break;
										default:
											_throw ( new TaskGuardConditionException($task,$guard_task,$key,"invalid data type in guard value[$value]" ) );
										}
									}
									if ( $guard_task->$key !== $value ){
										// ガード条件を満たさないので処理しない
										$type = gettype($guard_task->$key);
										$type2 = gettype($value);
										log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] guard task[$guard_task] status[$key => $type:{$guard_task->$key}] did not meet the guard conditions: task[$tid] status[$key => $type2:{$value}].");
										$process = FALSE;
										break 2;
									}
								}
							}
						}
					}
					if ( !$process ){
						continue;
					}
*/


					// task timer start
					Charcoal_Benchmark::start();

					$result = NULL;
					try{
						$result = $task->processEvent( $context );
					}
					catch( Exception $e ){
						_catch( $e );
						
						log_warning( "system,event,error", "[loop:$loop_id/$event_name/$task_name] an exception(" . get_class($e) . ") was raised:" . $e->getMessage() );

						$ret = $task->handleException( $e );

						if ( $ret === TRUE || ($ret instanceof Charcoal_Boolean) && $ret->isTrue() ){
							if ( $debug ) log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] exception was handled by task's exception handler." );
						}
						else if ( $ret instanceof Charcoal_IEvent ){
							$queue->enqueue( $ret );
							if ( $debug ) log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] exception was handled by task's exception handler, but event object was returned." );
						}
						else if ( is_string($ret) || ($ret instanceof Charcoal_String) ){
							$e = $this->getSandbox->createEvent( $ret );
							$queue->enqueue( $ret );
							if ( $debug ) log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] exception was handled by task's exception handler, but event name was returned." );
						}
						else{
							$e = $this->getSandbox()->createEvent( 'exception', array($e) );
							$queue->enqueue( $e );
							if ( $debug ) log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] exception was not handled by task's exception handler.enqued an exception event." );
						}
					}

					// task timer stop
					$elapse = Charcoal_Benchmark::stop();

					// result value handling
					if ( $result )
					{
						if ( $debug ) log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] event was processed by task. result=[$result] time=[$elapse]sec." );

						if ( $result === FALSE || $result === TRUE || ($result instanceof Charcoal_Boolean) ){
						}
						else if ( is_string($result) || ($result instanceof Charcoal_String) ){
							$e = $this->getSandbox()->createEvent( $result );
							$queue->enqueue( $e );
							if ( $debug ) log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] an event($result) is enqueued." );
						}
						else if ( $result instanceof Charcoal_IEvent ){
							$queue->enqueue( $result );
							if ( $debug ) log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] an event($result) is enqueued." );
						}
						else{
							_throw( new Charcoal_ProcessEventException( $event, $task, $result, "processEvent() must return a [boolean] or [IEvent] value." ) );
						}
					}

					// ポストアクション
					$post_actions = $task->getPostActions();

					log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] task post actions: $post_actions" );	
					if ( $post_actions )
					{
						foreach( $post_actions as $key => $action )
						{
							$target = NULL;

							if ( strpos(":",$action) !== FALSE ){
								list( $action, $target ) = explode( ":", trim($action) );
								log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] post action[$action] with target[$target].");	
							}
							else{
								$action = trim($action);
								if ( $debug ) log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] post action[$action].");	
							}
							switch( $action ){
							case "remove_task";
								// タスク実行リストからタスクを削除
								if ( !$target ){
									$target = $task_id;
								}
								if ( $target == $task_id ){
									if ( $debug ) log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] removing task: [$target]" );
									unset( $this->tasks["$target"] );
									if ( $debug ) log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] task was unregistered from execution list: [$this->tasks]" );
								}
								break;
							case "remove_event":
								// イベントを削除
								if ( !$target ){
									$target = $event_id;
								}
								if ( $target == $event_id ){
									if ( $debug ) log_info( "event", "[loop:$loop_id/$event_name/$task_name] event[$target] was removed.");		
									$event = NULL;
								}
								break;
							case "continue_event":
								// イベントをキューに再投入						
								break;
							}
						}
					}
					else{
						if ( $debug ) log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] no post action is  defined for event.");	
					}

					// イベント削除ならループを抜ける
					if ( !$event ){
						break;
					}

					// アボートイベントならループを抜ける
					if ( $result instanceof Charcoal_AbortEvent ){
						$exit_code  = $result->getExitCode()->getValue();
						$abort_type = $result->getAbortType()->getValue();
						if ( $debug ) log_info( "event", "event loop aborted(exit code:$exit_code");
						if ( $abort_type == Event::ABORT_TYPE_IMMEDIATELY ){
							log_warning( "system,event", "[loop:$loop_id/$event_name/$task_name] aborting by request immediately.");
							break 2;
						}
						else if ( $abort_type == Event::ABORT_TYPE_AFTER_THIS_LOOP ){
							log_warning( "system,event", "[loop:$loop_id/$event_name/$task_name] aborting by request after current loop end.");
							$abort_after_this_loop = true;
						}
					}

				} // task loop end

				// このループ終了時にアボートのフラグが成立していれば抜ける
				if ( $abort_after_this_loop ){
					log_warning( "system,event", "[loop:$loop_id/$event_name] aborting by flag.");
					break;
				}

				if ( $event ){
					// if current event is exception event, throw original exception
					if ( $event instanceof Charcoal_ExceptionEvent ){
						$e = $event->getException();
						_throw( $e );
					}

					// push back the event into our event queue
					$queue->enqueue( $event );
				}

				log_info( "system,event", "[loop:$loop_id/$event_name] event loop end.");

			} // event loop end

			if ( $queue->isEmpty() ){
				if ( $debug ) log_info( "system,event", "event queue is empty.");
				$exit_code = Charcoal_Event::EXIT_CODE_OK;
			}

			// ログ
			$elapse = Charcoal_Benchmark::stop();
			if ( $debug ) log_info( "system,event", "event loop end. time=[$elapse]msec.");
//		}
//		catch( Exception $ex ){
//			print "catch: $ex <BR>";
//		}

		if ( $debug ) log_info( "system,event", "processEvents end: exit_code=" . print_r($exit_code,true) );

		return $exit_code;
	}



}


