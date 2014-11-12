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
	private $sequence;
	private $layout_manager;

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$this->use_session         = $config->getBoolean( 'use_session', TRUE );
		$this->sequence            = $config->getString( 'sequence', '' );
		$layout_manager            = $config->getString( 'layout_manager' );

		$this->setLayoutManager( $layout_manager );

		if ( $this->getSandbox()->isDebug() )
		{
			log_info( "system,config", "procedure", "use_session:" . $this->use_session );
			log_info( "system,config", "procedure", "sequence:" . $this->sequence );
			log_info( "system,config", "procedure", "layout_manager:" . $this->layout_manager );
		}
	}

	/*
	 * レイアウトマネージャを取得
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

	/*
	 * プロシージャを実行する
	 */
	public function execute( $request, $response, $session = NULL )
	{
		Charcoal_Benchmark::start();

//		Charcoal_ParamTrait::checkImplements( 1, 'Charcoal_IRequest', $request );
//		Charcoal_ParamTrait::checkImplements( 2, 'Charcoal_IResponse', $response );
//		Charcoal_ParamTrait::checkIsA( 3, 'Charcoal_Session', $session, TRUE );

		$proc_path = $this->getObjectPath();
		$proc_name = $proc_path->toString();

//		log_info( "system", "procedure",  "プロシージャ[$proc_name]を実行します。" );

		//=======================================
		// タスクマネージャの作成
		//

		// タスクマネージャを作成
		$task_manager_name = $this->task_manager;
		$task_manager = $this->getSandbox()->createObject( $task_manager_name, 'task_manager' );

//		log_info( "system", "procedure", "タスクマネージャ[$task_manager_name]を作成しました。" );

		//=======================================
		// modules以下にクラスファイルがあればロードする
		//

//		log_info( "system", "procedure", 'loading module at procedure path:' . $proc_path );
		Charcoal_ModuleLoader::loadModule( $this->getSandbox(), $proc_path, $task_manager );
//		log_info( "system", "procedure", 'loaded module at procedure path:' . $proc_path );

		//=======================================
		// 追加モジュールのロード
		//

		if ( $this->modules ) {
//			log_info( "system", "procedure", 'loading additional modules: ' . $this->modules . " of procedure: " . $proc_path );

			foreach( $this->modules as $module_name ) {
				if ( strlen($module_name) === 0 )    continue;
				// load module
				Charcoal_ModuleLoader::loadModule( $this->getSandbox(), $module_name, $task_manager );
			}
	
//			log_info( "system", "procedure", 'loaded additional modules.' );
		}

		//=======================================
		// ステートフルタスクの復帰
		//

		$use_session = $this->getSandbox()->getProfile()->getBoolean( 'USE_SESSION' );

		if ( $use_session->isTrue() ){
//			log_info( "system", "procedure", 'ステートフルタスクの復元を開始します。' );

			$task_manager->restoreStatefulTasks( $session );

//			log_info( "system", "procedure", 'ステートフルタスクを復元しました。' );
		}

		//=======================================
		// シーケンスの復帰
		//

		$sequence = NULL;
		$globalsequence = NULL;
		$localsequence = NULL;

		if ( $use_session->isTrue() ){

			$seq_name = us($this->sequence);
			$seq_name = strlen($seq_name) > 0 ? $seq_name : 'local';

			// restore global sequence
//			log_info( "system,sequence", "sequence", "starting restoring global sequence." );

			$data_id = 'sequence://global';
			$global_seq = NULL;
			if ( isset($_SESSION[ $data_id ]) ){
				$data = $_SESSION[ $data_id ];
				$data = unserialize( $data );
				if ( $data instanceof Charcoal_Sequence ){
					$global_seq = $data;
//					log_info( "debug,sequence", "sequence", "restored global sequence:" . print_r($globalsequence,true) );
				}
			}

			// restore local sequence
//			log_info( "system,sequence", "sequence", "starting restoring local sequence." );

			// 復元
			$data_id = 'sequence://' . $seq_name;
			$local_seq = NULL;
			if ( isset($_SESSION[ $data_id ]) ){
				$data = $_SESSION[ $data_id ];

				$data = unserialize( $data );
				if ( $data instanceof Charcoal_Sequence ){
					$local_seq = $data;
//					log_info( "debug,sequence",  "restored local sequence:" . print_r($localsequence,true) );
				}
			}

			// merge global and local sequence
			$global_seq = $global_seq ? $global_seq : new Charcoal_Sequence();
			$local_seq  = $local_seq ? $local_seq : new Charcoal_Sequence();

			$sequence = new Charcoal_SequenceHolder( $global_seq, $local_seq );
		}

		//=======================================
		// create system event(request event)
		//

//		log_info( "system,debug,event", 'creating reqyest event.', 'event' );

		// create request event
		$event = $this->getSandbox()->createEvent( 'request', array($request) );
		$task_manager->pushEvent( $event );

//		log_info( "system,debug,event", 'pushed reqyest event to the event queue.', 'event' );

		//=======================================
		// ユーザイベントの作成
		//
//		log_info( "system", "procedure", 'ユーザイベントの作成処理を開始します。' );

		$event_list = array();

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

//		log_info( "system", "procedure", "タスクマネージャによるイベント処理を開始します。" );

		$context = new Charcoal_EventContext( $this->getSandbox() );

		$context->setProcedure( $this );
		$context->setRequest( $request );
		$context->setSequence( $sequence );
		$context->setResponse( $response );
		$context->setEventQueue( $task_manager->getEventQueue() );
		$context->setTaskManager( $task_manager );

		$exit_code = $task_manager->processEvents( $context );
		if ( !is_int($exit_code) && !($exit_code instanceof Charcoal_Integer) ){
//			log_info( "system", "procedure", "異常な終了コードを検知しました。(" . gettype($exit_code) . ")。タスクマネージャは終了コードとして整数値のみ返却することができます。" );
			_throw( new Charcoal_BadExitCodeException( $exit_code ) );
		}

//		log_info( "system", "procedure", "タスクマネージャによるイベント処理が完了しました。終了コード($exit_code)" );

		//=======================================
		// 終了処理
		//

		if ( $use_session->isTrue() ){

			$seq_name = us($this->sequence);
			$seq_name = strlen($seq_name) > 0 ? $seq_name : 'local';

			// globalシーケンスの保存
			$data_id = 'sequence://global';
			$session->set( s($data_id), $global_seq );

//			log_info( "debug,sequence",  "globalsequence:" . print_r($globalsequence,true) );
//			log_info( "system,sequence", "globalシーケンスを保存しました。" );

			// localシーケンスの保存
			$data_id = 'sequence://' . $seq_name;
			$session->set( s($data_id), $local_seq );

//			log_info( "debug,sequence",  "localsequence:" . print_r($localsequence,true) );
//			log_info( "system,sequence", "localシーケンス[$seq_name]を保存しました。" );
		}

		// セッション情報の保存
		if ( $use_session->isTrue() )
		{
			// ステートフルタスクの保存
			$task_manager->saveStatefulTasks( $session );
		}

		$score = Charcoal_Benchmark::stop();
		log_debug( 'system, debug', "procedure execute method end: [$score] msec" );

//		log_info( "system", "procedure", "プロシージャ[$proc_name]を実行しました。" );
	}
}

