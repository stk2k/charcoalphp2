<?php
/**
* タスクの基底クラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

abstract class Charcoal_Task extends Charcoal_CharcoalObject implements Charcoal_ITask
{
	private $_name_space;
	private $_guard_conditions;
	private $_event_filters;
	private $_post_actions;
	private $_priority;

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_name_space        = NULL;
		$this->_guard_conditions  = v(array());
		$this->_event_filters     = v(array());
		$this->_post_actions      = v(array());
		$this->_priority          = 0;
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
		$this->_name_space         = $config->getString( s('name_space'), s('') );
		$this->_event_filters      = $config->getArray( s('event_filters'), v(array()) );
		$this->_post_actions       = $config->getArray( s('post_actions'), v(array()) );
		$this->_priority           = $config->getInteger( s('priority'), i(0) );

		log_debug( "debug", "Task[$this] name space: {$this->_name_space}" );
		log_debug( "debug", "Task[$this] event filters: {$this->_event_filters}" );
		log_debug( "debug", "Task[$this] post actions: {$this->_post_actions}" );
		log_debug( "debug", "Task[$this] priority: {$this->_priority}" );
	}

	/**
	 * 名前空間を取得する
	 */
	public function getNameSpace()
	{
		return $this->_name_space;
	}

	/**
	 * ユーザイベントを作成する
	 */
	public function createUserEvent( Charcoal_String $event_name )
	{
		return Charcoal_Factory::createObject( s($event_name), s('event') );
	}

	/**
	 * ガード条件を取得する
	 */
	public function getGuardConditions()
	{
		return $this->_guard_conditions;
	}

	/**
	 * イベントフィルタを取得する
	 */
	public function getEventFilters()
	{
		return $this->_event_filters;
	}

	/**
	 * ポストアクションを取得する
	 */
	public function getPostActions()
	{
		return $this->_post_actions;
	}

	/**
	 * 実行優先度を取得する
	 */
	public function getPriority()
	{
		return $this->_priority;
	}

	/**
	 * レンダリングレイアウトイベントを作成
	 */
	public function createRenderEvent( Charcoal_IProcedure $procedure, Charcoal_String $layout_name )
	{
		// レイアウトマネージャを取得
		$layout_manager = $procedure->getLayoutManager();

		// レンダリングイベントを作成
		$event = Charcoal_Factory::createEvent( s('render_layout') );

		$layout = $layout_manager->getLayout( $layout_name );
		$event->setLayout( $layout );

		return $event;
	}

	/*
	 *	リダイレクトイベントを作成する
	 */
	public function createRedirectedLayoutEvent( Charcoal_ObjectPath $obj_path, Charcoal_Properties $params = NULL )
	{
		// レンダリングイベントを作成
		$event = Charcoal_Factory::createEvent( s('render_layout') );

		$event->setLayout( new Charcoal_ProcedureRedirectLayout($obj_path,$params) );

		return $event;
	}


	/*
	 *	URLリダイレクトイベントを作成する
	 */
	public function createURLRedirectedLayoutEvent( Charcoal_String $url )
	{
		// レンダリングイベントを作成
		$event = Charcoal_Factory::createEvent( s('render_layout') );

		$event->setLayout( new Charcoal_URLRedirectLayout($url) );

		return $event;
	}
}

return __FILE__;