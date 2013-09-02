<?php
/**
* HTTPプローシージャ
*
* PHP version 5
*
* @package    procedures
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_HttpProcedure extends Charcoal_CharcoalObject implements Charcoal_IProcedure
{
	private $_task_manager;
	private $_use_session;
	private $_forward_target;
	private $_sequence;
	private $_modules;
	private $_events;
	private $_layout_manager;
	private $_response_filters;
	private $_log_enabled;

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
		$this->_task_manager        = $config->getString( s('task_manager'), s('') );
		$this->_use_session         = $config->getBoolean( s('use_session'), b(TRUE) );
		$this->_forward_target      = $config->getString( s('forward_target'), s('') );
		$this->_sequence            = $config->getString( s('sequence'), s('') );
		$this->_modules             = $config->getArray( s('modules'), v(array()) );
		$this->_events              = $config->getArray( s('events'), v(array()) );
		$this->_response_filters    = $config->getArray( s('response_filters'), v(array()) );
		$this->_log_enabled         = $config->getBoolean( s('log_enabled'), b(TRUE) );

		$layout_manager      = $config->getString( s('layout_manager') );
		$this->setLayoutManager( s($layout_manager) );

		// eventsに記載しているイベントのモジュールも読み込む
		if ( $this->_events ){
			foreach( $this->_events as $event ){
				$pos = strpos( $event, "@" );
				if ( $pos !== FALSE ){
					$module_name = substr( $event, $pos );
					$this->_modules[] = $module_name;
				}
			}
		}

//		log_info( "system,config", "procedure", "task_manager:" . $this->_task_manager );
//		log_info( "system,config", "procedure", "use_session:" . $this->_use_session );
//		log_info( "system,config", "procedure", "forward_target:" . $this->_forward_target );
//		log_info( "system,config", "procedure", "sequence:" . $this->_sequence );
//		log_info( "system,config", "procedure", "modules:" . $this->_modules );
//		log_info( "system,config", "procedure", "events:" . $this->_events );
//		log_info( "system,config", "procedure", "layout_manager:" . $this->_layout_manager );
//		log_info( "system,config", "procedure", "response_filters:" . print_r($this->_response_filters,true) );

	}

	/*
	 * レイアウトマネージャを取得
	 */
	public function getLayoutManager()
	{
		return $this->_layout_manager;
	}

	/*
	 * レイアウトマネージャを設定
	 */
	public function setLayoutManager( Charcoal_String $layout_manager )
	{
		if ( !$layout_manager->isEmpty() ){
			$this->_layout_manager = Charcoal_Factory::CreateObject( s($layout_manager), s('layout_manager') );
		}
	}

	/*
	 * 転送先があるか
	 */
	public function hasForwardTarget()
	{
		return !$this->_forward_target->isEmpty();
	}

	/*
	 * 転送先を取得
	 */
	public function getForwardTarget()
	{
		return new Charcoal_ObjectPath( $this->_forward_target );
	}

	/*
	 * プロシージャを実行する
	 */
	public function execute( Charcoal_IRequest $request, Charcoal_IResponse $response, Charcoal_Session $session )
	{
		$proc_path = $this->getObjectPath();
		$proc_name = $proc_path->toString();

//		log_info( "system", "procedure",  "プロシージャ[$proc_name]を実行します。" );

		//=======================================
		// ログの無効化
		//

		if ( $this->_log_enabled->isFalse() ){
			Charcoal_Logger::clear();
		}

		//=======================================
		// タスクマネージャの作成
		//

		// タスクマネージャを作成
		$task_manager_name = $this->_task_manager;
		$task_manager = Charcoal_Factory::createObject( s($task_manager_name), s('task_manager') );

//		log_info( "system", "procedure", "タスクマネージャ[$task_manager_name]を作成しました。" );

		//=======================================
		// modules以下にクラスファイルがあればロードする
		//

//		log_info( "system", "procedure", 'loading module at procedure path:' . $proc_path );
		Charcoal_ModuleLoader::loadModule( $proc_path, $task_manager );
//		log_info( "system", "procedure", 'loaded module at procedure path:' . $proc_path );

		//=======================================
		// 追加モジュールのロード
		//

		if ( $this->_modules ) {
//			log_info( "system", "procedure", 'loading additional modules: ' . $this->_modules . " of procedure: " . $proc_path );

			foreach( $this->_modules as $module_name ) {
//				log_info( "system", "procedure", 'loading module: ' . $module_name );
				// モジュールのロード
				$obj_path = new Charcoal_ObjectPath( s($module_name) );
				Charcoal_ModuleLoader::loadModule( $obj_path, $task_manager );
			}
	
//			log_info( "system", "procedure", 'loaded additional modules.' );
		}

		//=======================================
		// レスポンスフィルタのロード
		//

		if ( $this->_response_filters ) {
//			log_info( "system", "procedure", 'プロシージャの追加レスポンスフィルタを読み込みます。' );

			foreach( $this->_response_filters as $filter_name ) {
				// モジュールのロード
				$filter = Charcoal_Factory::createObject( s($filter_name), s('response_filter') );
				// リストに追加
				$response->addResponseFilter( $filter );
			}
	
//			log_info( "system", "procedure", 'プロシージャの追加レスポンスフィルタを読み込みました。' );
		}

		//=======================================
		// ステートフルタスクの復帰
		//
		$use_session = Charcoal_Profile::getBoolean(s('USE_SESSION'))->isTrue();

		if ( $use_session && $this->_use_session->getValue() ){
//			log_info( "system", "procedure", 'ステートフルタスクの復元を開始します。' );

			$task_manager->restoreStatefulTasks( $session );

//			log_info( "system", "procedure", 'ステートフルタスクを復元しました。' );
		}

		//=======================================
		// シーケンスの復帰
		//

		$global_sequence = new Charcoal_Sequence();
		$local_sequence = new Charcoal_Sequence();

		$seq_name = us($this->_sequence);
		$seq_name = strlen($seq_name) > 0 ? $seq_name : 'local';

		if ( $use_session ){

			// restore global sequence
//			log_info( "system,sequence", "sequence", "starting restoring global sequence." );

			$data_id = 'sequence://global';
			if ( isset($_SESSION[ $data_id ]) ){
				$data = $_SESSION[ $data_id ];
				$data = unserialize( $data );
				if ( $data instanceof Charcoal_Sequence ){
					$global_sequence = $data;
//					log_info( "debug,sequence", "sequence", "restored global sequence:" . print_r($global_sequence,true) );
				}
			}

			// restore local sequence
//			log_info( "system,sequence", "sequence", "starting restoring local sequence." );

			// 復元
			$data_id = 'sequence://' . $seq_name;
			if ( isset($_SESSION[ $data_id ]) ){
				$data = $_SESSION[ $data_id ];

				$data = unserialize( $data );
				if ( $data instanceof Charcoal_Sequence ){
					$local_sequence = $data;
//					log_info( "debug,sequence",  "restored local sequence:" . print_r($local_sequence,true) );
				}
			}
		}

		// merge global and local sequence
		$sequence = new Charcoal_SequenceHolder( $global_sequence, $local_sequence );

		//=======================================
		// create system event(request event)
		//

		// create request event
		$event = Charcoal_Factory::createEvent( s('request'), v(array($request)) );
		$task_manager->pushEvent( $event );

		//=======================================
		// ユーザイベントの作成
		//
//		log_info( "system", "procedure", 'ユーザイベントの作成処理を開始します。' );

		$event_list = array();

		if ( $this->_events )
		{
			foreach( $this->_events as $event_name )
			{
				$event = Charcoal_Factory::createEvent( s($event_name) );
				$task_manager->pushEvent( $event );
			}
		}

		//=======================================
		// イベント処理
		//

//		log_info( "system", "procedure", "タスクマネージャによるイベント処理を開始します。" );

		$context = new Charcoal_EventContext();

		$context->setProcedure( $this );
		$context->setRequest( $request );
		$context->setSequence( $sequence );
		$context->setResponse( $response );

		$exit_code = $task_manager->processEvents( $context );
		if ( !is_int($exit_code) && !($exit_code instanceof Charcoal_Integer) ){
//			log_info( "system", "procedure", "異常な終了コードを検知しました。(" . gettype($exit_code) . ")。タスクマネージャは終了コードとして整数値のみ返却することができます。" );
			_throw( new Charcoal_BadReturnValueTypeException( $exit_code, s('Integer') ) );
		}

//		log_info( "system", "procedure", "タスクマネージャによるイベント処理が完了しました。終了コード($exit_code)" );

		//=======================================
		// 終了処理
		//

		if ( $use_session ){

			// globalシーケンスの保存
			$data_id = 'sequence://global';
			$session->set( s($data_id), $global_sequence );

//			log_info( "debug,sequence",  "global_sequence:" . print_r($global_sequence,true) );
//			log_info( "system,sequence", "globalシーケンスを保存しました。" );

			// localシーケンスの保存
			$data_id = 'sequence://' . $seq_name;
			$session->set( s($data_id), $local_sequence );

//			log_info( "debug,sequence",  "local_sequence:" . print_r($local_sequence,true) );
//			log_info( "system,sequence", "localシーケンス[$seq_name]を保存しました。" );
		}

		// セッション情報の保存
		if ( $use_session )
		{
			// ステートフルタスクの保存
			$task_manager->saveStatefulTasks( $session );
		}

//		log_info( "system", "procedure", "プロシージャ[$proc_name]を実行しました。" );
	}
}

