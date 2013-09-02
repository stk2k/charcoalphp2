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
class Charcoal_SimpleProcedure extends Charcoal_CharcoalObject implements Charcoal_IProcedure
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
	public function configure( Charcoal_Config $config )
	{
		$this->_task_manager        = $config->getString( s('task_manager'), s('') );
		$this->_forward_target      = $config->getString( s('forward_target'), s('') );
		$this->_modules             = $config->getArray( s('modules'), v(array()) );
		$this->_events              = $config->getArray( s('events'), v(array()) );
		$this->_debug_mode          = $config->getBoolean( s('debug_mode'), b(FALSE) );

		$layout_manager      = $config->getString( s('layout_manager'), s('') );
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

		log_info( "system,config",  "task_manager：" . $this->_task_manager );
		log_info( "system,config",  "forward_target：" . $this->_forward_target );
		log_info( "system,config",  "modules：" . $this->_modules );
		log_info( "system,config",  "events：" . $this->_events );
		log_info( "system,config",  "_debug_mod：" . $this->_debug_mode );
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
		return new ProcedurePath( $this->_forward_target );
	}

	/*
	 * プロシージャを実行する
	 */
	public function execute( Charcoal_IRequest $request, Charcoal_IResponse $response, Charcoal_Session $session )
	{
		$proc_path = $this->getObjectPath();
		$proc_name = $proc_path->toString();

		log_info( "system,event",  "プロシージャ[$proc_name]を実行します。" );

		//=======================================
		// タスクマネージャの作成
		//

		// タスクマネージャを作成
		$task_manager_name = $this->_task_manager;
		$task_manager = Charcoal_Factory::createObject( s($task_manager_name), s('task_manager') );

		log_info( "system,event", "タスクマネージャ[$task_manager_name]を作成しました。" );

		//=======================================
		// 追加モジュールのロード
		//

		if ( $this->_modules ) {
			log_info( "system",  '追加モジュールを読み込みます。' );

			foreach( $this->_modules as $module ) {
				// モジュールのロード
				$module_path = new Charcoal_ObjectPath( s($module) );
				Charcoal_ModuleLoader::loadModule( $module_path, $task_manager );
			}
	
			log_info( "system",  '追加モジュールを読み込みました。' );
		}

		//=======================================
		// modules以下にクラスファイルがあればロードする
		//

		$module_path = $proc_path->getVirtualPath();
		$module_path = new Charcoal_ObjectPath( s("@{$module_path}") );
		log_info( "system",  'modules以下のモジュールを読み込みます。モジュールパス:' . $module_path );
		Charcoal_ModuleLoader::loadModule( $module_path, $task_manager );
		log_info( "system",  'modules以下のモジュールを読み込みました。' );

/*
		log_info( "system",  'modules以下のモジュールを読み込みます。モジュールパス:' . $proc_path );
		Charcoal_ModuleLoader::loadModule( $proc_path, $task_manager );
		log_info( "system",  'modules以下のモジュールを読み込みました。' );
*/

		//=======================================
		// ユーザイベントの作成
		//
		log_info( "system,event", 'ユーザイベントの作成処理を開始します。' );

		$event_list = NULL;

		if ( $this->_events ) {
			foreach( $this->_events as $event_name ) {

				// ユーザイベントを作成
				$event = Charcoal_Factory::createObject( s($event_name), s('event') );

				// ユーザイベントの追加
				$event_list[] = $event;

				log_info( "system,event",  "ユーザイベント[$event_name]を追加しました。" );
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

		$context = new Charcoal_EventContext();

		$context->setProcedure( $this );
		$context->setRequest( $request );
		$context->setResponse( $response );

		$exit_code = $task_manager->processEvents( $context );
		if ( !is_int($exit_code) && !($exit_code instanceof Charcoal_Integer) ){
			log_info( "system",  "異常な終了コードを検知しました。(" . gettype($exit_code) . ")。タスクマネージャは終了コードとして整数値のみ返却することができます。" );
			_throw( new Charcoal_BadReturnValueTypeException( $exit_code, s('Integer') ) );
		}

		log_info( "system", "タスクマネージャによるイベント処理が完了しました。終了コード($exit_code)" );

		//=======================================
		// 終了処理
		//

		log_info( "system", "プロシージャ[$proc_name]を実行しました。" );
	}
}
