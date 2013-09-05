<?php
/**
* 単純なタスクマネージャを実装するクラス
*
* PHP version 5
*
* @package    task_managers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_DefaultTaskManager extends Charcoal_CharcoalObject implements Charcoal_ITaskManager
{
	private $_tasks;
	private $_queue;

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_tasks  = new Charcoal_Vector();
		$this->_queue  = new Charcoal_EventQueue();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
		$this->_tasks  = new Charcoal_Vector();
		$this->_queue  = new Charcoal_EventQueue();
	}

	/*
	 * タスクを登録する
	 */
	public function registerTask( Charcoal_String $key, Charcoal_ITask $task )
	{
		$key = us( $key );
		if ( isset($this->_tasks[$key]) ){
			log_warning( "system,event", "タスク[$key]は登録済みです。" );
			return;
		}

		// タスクを保存
		$this->_tasks[$key] = $task;

		log_info( "system,event", "registered task[$task] as [$key]" );
	}

	/*
	 * タスクを登録を解除する
	 */
	public function unregisterTask( Charcoal_String $key )
	{
		$key = $key;

		// タスクを削除
		unset( $this->_tasks[$key] );
	}

	/*
	 * タスクが登録されているか
	 */
	public function isTaskRegistered( Charcoal_String $key )
	{
		$key = $key;

		return isset($this->_tasks[$key]);
	}

	/*
	 * タスクを取得する
	 */
	public function getTask( Charcoal_String $task_name )
	{
		$task_name = $task_name;

		if ( isset($this->_tasks[$task_name]) ){
			return $this->_tasks[$task_name];
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
		foreach( $this->_tasks as $task ){
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
		foreach( $this->_tasks as $task ){
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
		$this->_queue->enqueue( $event );
		log_info( "system,event", "event[" . $event . "] was enqueued." );
	}

	/**
	 *   タスクに対してイベント処理を行う
	 *
	 */
	public function processTaskEvent( Charcoal_ITask $task, Charcoal_IEventContext $context  )
	{
		$result = $task->processEvent( $context );

		return $result;
	}

	/**
	 *   イベント処理を行う
	 *
	 */
	public function processEvents( Charcoal_IEventContext $context  )
	{
		log_info( "system,event", "processEvents start." );

		$procedure = $context->getProcedure();
		$request   = $context->getRequest();
		$sequence  = $context->getSequence();
		$response  = $context->getResponse();

		$exit_code = 0;

//		try{
			$queue = $this->_queue;

			// タスク実行リスト
			$task_exec_list = clone $this->_tasks;

			// 最大イベント処理回数
			$max_event_loop = Charcoal_Profile::getInteger( s("TM_MAX_EVENT_LOOP"), i(1000) )->getValue();

			Charcoal_Benchmark::start( 'total' );

			$loop_id = 0;
			while( !$queue->isEmpty() ){

				$abort_after_this_loop = FALSE;

				// イベント一覧を優先度でソートする
				$queue->sortByPriority();

				// ログ
				log_info( "system,event", "[loop:$loop_id] event queue: [ " . Charcoal_System::implodeArray( ",", $queue->toArray() ) . " ]" );

				// イベントを取得
				$event      = $queue->dequeue();
				$event_name = $event->getObjectName();
				$event_id   = $event->getObjectPath()->getObjectPathString();

				$context->setEvent( $event );

				// ログ
				log_info( "system,event", "[loop:$loop_id/$event_name] starting process event[$event]" );

				// タスクの一覧を退避
				$next_task_exec_list = clone $task_exec_list;

				// タスク一覧を優先度でソートする
				$key_priority = array();
				foreach ( $task_exec_list as $key => $task ){
					$key_priority[$key] = ui( $task->getPriority() );
				}
				$a_task_exec_list = uv($task_exec_list);
				array_multisort( $key_priority,SORT_DESC, $a_task_exec_list );
				$task_exec_list = v($a_task_exec_list);

				// すべてのタスクにイベントをディスパッチする
				log_info( "system,event", "[loop:$loop_id/$event_name] task list: [$task_exec_list]" );
				foreach( $task_exec_list as $task ){

					$task_name = $task->getObjectName();
					$task_id   = $task->getObjectPath()->getObjectPathString();
					log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] event[$event_name] is dispatching to task[$task_name].");

					// イベントフィルタ
					$process = FALSE;
					$event_filters = $task->getEventFilters();
					log_info( "event", "[loop:$loop_id/$event_name/$task_name] task event filter: " . $event_filters );
					foreach( $event_filters as $filter ){
						if ( $event_id == us($filter) ){
							$process = TRUE;
							break;
						}
					}

					if ( !$process ){
						log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] event[$event_name] is NOT found in task's event filters: [$event_filters]. Passing this task.");
						continue;
					}
					log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] event[$event_name] is found in task's event filters: [$event_filters].");

					// ガード条件判定
					$process = TRUE;
					$guard_conditions = $task->getGuardConditions();
					log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] task guard conditions are : [$guard_conditions]" );
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

					if ( !$process ){
						continue;
					}

					// task timer start
					Charcoal_Benchmark::start( 'task' );

					$result = $this->processTaskEvent( $task, $context );

					// task timer stop
					$elapse = Charcoal_Benchmark::stop( 'task' );

					// ログ
					log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] event was processed by task. result=[$result] time=[$elapse]sec." );

					// 結果がIEventでもBooleanでもなければエラー
					if ( $result !== FALSE && $result !== NULL ){
						if ( $result instanceof Charcoal_IEvent ){
							// イベントをキューに再投入
							$queue->enqueue( $result );
							log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] event[$result] was enqueued[$queue].");	
						}
						else if ( !($result instanceof Charcoal_Boolean) ){
							_throw( new Charcoal_ProcessEventException( $event, $task, $result, "ITask::processEvent() must return a [Boolean] or [IEvent] value." ) );
						}
					}

					// ポストアクション
					$post_actions = $task->getPostActions();
					log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] task post actions: " . $post_actions );	
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
								log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] post action[$action].");	
							}
							switch( $action ){
							case "remove_task";
								// タスク実行リストからタスクを削除
								if ( !$target ){
									$target = $task_id;
								}
								if ( $target == $task_id ){
									unset( $next_task_exec_list[$target] );
									log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] task was unregistered from execution list: [$next_task_exec_list]" );
								}
								break;
							case "remove_event":
								// イベントを削除
								if ( !$target ){
									$target = $event_id;
								}
								if ( $target == $event_id ){
									log_info( "event", "[loop:$loop_id/$event_name/$task_name] event[$target] was removed.");		
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
						log_info( "system,event", "[loop:$loop_id/$event_name/$task_name] no post action is  defined for event.");	
					}

					// イベント削除ならループを抜ける
					if ( !$event ){
						break;
					}

					// アボートイベントならループを抜ける
					if ( $result instanceof Charcoal_AbortEvent ){
						$exit_code  = $result->getExitCode()->getValue();
						$abort_type = $result->getAbortType()->getValue();
						log_info( "event", "event loop aborted(exit code:$exit_code");
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

				// 次のイベントで使うタスク実行リスト
				$task_exec_list = $next_task_exec_list;

				// イベントカウントのインクリメント
				$loop_id ++;
				// イベントカウントが最大を超えていたらワーニングを出して終了
				if ( $loop_id > $max_event_loop ){
					log_warning( "system,event", "[loop:$loop_id/$event_name] aborting by overflow maximum loop count[$max_event_loop].");
					break;
				}

				// このループ終了時にアボートのフラグが成立していれば抜ける
				if ( $abort_after_this_loop ){
					log_warning( "system,event", "[loop:$loop_id/$event_name] aborting by flag.");
					break;
				}

			} // event loop end

			if ( $queue->isEmpty() ){
				log_info( "system,event", "event queue is empty.");
				$exit_code = Charcoal_Event::EXIT_CODE_OK;
			}

			// ログ
			$elapse = Charcoal_Benchmark::stop( 'total' );
			log_info( "system,event", "event loop end. time=[$elapse]msec.");
//		}
//		catch( Exception $ex ){
//			print "catch: $ex <BR>";
//		}

		log_info( "system,event", "processEvents end: exit_code=" . print_r($exit_code,true) );

		return $exit_code;
	}



}


