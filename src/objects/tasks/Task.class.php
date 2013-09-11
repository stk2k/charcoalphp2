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

abstract class Charcoal_Task extends Charcoal_CharcoalObject implements Charcoal_ITask, Charcoal_IExceptionHandler
{
	const TAG = "Task";

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
	public function configure( $config )
	{
		parent::configure( $config );

		$this->_name_space         = $config->getString( 'name_space', '' );
		$this->_event_filters      = $config->getArray( 'event_filters', array() );
		$this->_post_actions       = $config->getArray( 'post_actions', array() );
		$this->_priority           = $config->getInteger( 'priority', 0 );

		if ( $this->getSandbox()->isDebug() )
		{
			log_debug( "debug", "Task[$this] name space: {$this->_name_space}", self::TAG );
			log_debug( "debug", "Task[$this] event filters: " . implode( ',', $this->_event_filters ), self::TAG );
			log_debug( "debug", "Task[$this] post actions: " . implode( ',', $this->_post_actions ), self::TAG );
			log_debug( "debug", "Task[$this] priority: {$this->_priority}", self::TAG );
		}
	}

	/**
	 * execute exception handlers
	 * 
	 * @param Exception $e     exception to handle
	 * 
	 * @return boolean        TRUE means the exception is handled, otherwise FALSE
	 */
	public function handleException( $e )
	{
	}

	/**
	 * 名前空間を取得する
	 */
	public function getNameSpace()
	{
		return $this->_name_space;
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

