<?php
/**
* レンダリングイベント
*
* PHP version 5
*
* @package    objects.events
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_RenderEvent extends Charcoal_UserEvent implements Charcoal_IEvent
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

}

