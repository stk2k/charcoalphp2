<?php
/**
* レイアウトレンダリングイベント
*
* PHP version 5
*
* @package    objects.events
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_RenderLayoutEvent extends Charcoal_UserEvent implements Charcoal_IEvent
{
	private $_layout;

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
	public function configure( $config )
	{
		parent::configure( $config );

		$config->set( s('priority'), Charcoal_EnumEventPriority::VIEW_RENDERING );
	}

	/**
	 * 関連付けられたレイアウトを取得する
	 */
	public function getLayout()
	{
		return $this->_layout;
	}

	/**
	 * 関連付けられたレイアウトを設定する
	 */
	public function setLayout( Charcoal_Layout $layout )
	{
		$this->_layout = $layout;
	}
}
