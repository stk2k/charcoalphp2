<?php
/**
* セッションなしプローシージャ
*
* PHP version 5
*
* @package    procedures
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_SimpleProcedure extends Charcoal_AbstractProcedure
{
	private $_task_manager;
	private $_forward_target;
	private $_modules;
	private $_events;
	private $_debug_mode;

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/*
	 *	デバッグモード
	 */
	public function isDebugMode()
	{
		return $this->_debug_mode && $this->_debug_mode->isTrue() ? TRUE : FALSE;
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$this->_task_manager        = $config->getString( 'task_manager', '' );
		$this->_forward_target      = $config->getString( 'forward_target', '' );
		$this->_modules             = $config->getArray( 'modules', array() );
		$this->_events              = $config->getArray( 'events', array() );
		$this->_debug_mode          = $config->getBoolean( 'debug_mode', FALSE );

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

		if ( $this->getSandbox()->isDebug() )
		{
			log_info( "system,config",  "task_manager：" . $this->_task_manager );
			log_info( "system,config",  "forward_target：" . $this->_forward_target );
			log_info( "system,config",  "modules：" . $this->_modules );
			log_info( "system,config",  "events：" . $this->_events );
			log_info( "system,config",  "_debug_mod：" . $this->_debug_mode );
		}
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
	public function setLayoutManager( $layout_manager )
	{
		$this->_layout_manager = $this->getSandbox()->CreateObject( $layout_manager, 'layout_manager' );
	}

	/*
	 * 転送先があるか
	 */
	public function hasForwardTarget()
	{
		return strlen($this->_forward_target) > 0;
	}

	/*
	 * 転送先を取得
	 */
	public function getForwardTarget()
	{
		return new ProcedurePath( $this->_forward_target );
	}

	/*
	 * プロシージャを実行する
	 */
	public function execute( $request, $response, $session = NULL )
	{
		Charcoal_ParamTrait::checkImplements( 1, 'Charcoal_IRequest', $request );
		Charcoal_ParamTrait::checkImplements( 2, 'Charcoal_IResponse', $response );
		Charcoal_ParamTrait::checkIsA( 3, 'Charcoal_Session', $session, TRUE );

		$proc_path = $this->getObjectPath();
		$proc_name = $proc_path->toString();

		log_info( "system,event",  "プロシージャ[$proc_name]を実行します。" );

		//=======================================
		// タスクマネージャの作成
		//

		// タスクマネージャを作成
		$task_manager_name = $this->_task_manager;
		$task_manager = $this->getSandbox()->createObject( $task_manager_name, 'task_manager' );

		log_info( "system,event", "タスクマネージャ[$task_manager_name]を作成しました。" );

		//=======================================
		// 追加モジュールのロード
		//

		if ( $this->_modules ) {
			log_info( "system",  '追加モジュールを読み込みます。' );

			foreach( $this->_modules as $module_name ) {
				if ( strlen($module_name) === 0 )    continue;
				// モジュールのロード
				Charcoal_ModuleLoader::loadModule( $module_name, $task_manager );
			}
	
			log_info( "system",  '追加モジュールを読み込みました。' );
		}

		//=======================================
		// modules以下にクラスファイルがあればロードする
		//

		Charcoal_ModuleLoader::loadModule( $this->getSandbox(), $proc_path, $task_manager );

/*
		log_info( "system",  'modules以下のモジュールを読み込みます。モジュールパス:' . $proc_path );
		Charcoal_ModuleLoader::loadModule( $proc_path, $task_manager );
		log_info( "system",  'modules以下のモジュールを読み込みました。' );
*/

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
		log_info( "system,event", 'ユーザイベントの作成処理を開始します。' );

		$event_list = NULL;

		if ( $this->_events ) {
			foreach( $this->_events as $event_name ) {

				if ( strlen($event_name) === 0 )    continue;

				$event = $this->getSandbox()->createEvent( $event_name );

				$event_list[] = $event;
			}
		}

		log_info( "system,event", 'ユーザイベントの作成処理を終了します。' );

		// add events
		if ( $event_list && is_array($event_list) ){
			foreach( $event_list as $event ){
				$task_manager->pushEvent( $event );
			}
		}

		//=======================================
		// イベント処理
		//

		log_info( "system", "タスクマネージャによるイベント処理を開始します。" );

		$context = new Charcoal_EventContext( $this->getSandbox() );

		$context->setProcedure( $this );
		$context->setRequest( $request );
		$context->setResponse( $response );
		$context->setTaskManager( $task_manager );

		$exit_code = $task_manager->processEvents( $context );
		if ( !is_int($exit_code) && !($exit_code instanceof Charcoal_Integer) ){
			log_info( "system",  "異常な終了コードを検知しました。(" . gettype($exit_code) . ")。タスクマネージャは終了コードとして整数値のみ返却することができます。" );
			_throw( new Charcoal_BadExitCodeException( $exit_code ) );
		}

		log_info( "system", "タスクマネージャによるイベント処理が完了しました。終了コード($exit_code)" );

		//=======================================
		// 終了処理
		//

		log_info( "system", "プロシージャ[$proc_name]を実行しました。" );
	}

}

