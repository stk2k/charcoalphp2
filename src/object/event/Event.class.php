<?php
/**
* Basic event class
*
* PHP version 5
*
* @package    objects.events
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

abstract class Charcoal_Event extends Charcoal_CharcoalObject implements Charcoal_IEvent
{
	const EXIT_CODE_OK            = 0;
	const EXIT_CODE_ABORT         = 1;

	const ABORT_TYPE_IMMEDIATELY      = 0;		// このイベント直後にイベント処理停止
	const ABORT_TYPE_AFTER_THIS_LOOP  = 1;		// このイベントループ終了後にイベント処理停止

	private $_priority;

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();

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

		$this->_priority = $config->getInteger( 'priority', Charcoal_EnumEventPriority::NORMAL );
	}

	/**
	 * Set event priority
	 *
	 * @return int 
	 */
	public function setPriority( $priority )
	{
//		Charcoal_ParamTrait::checkInteger( 1, $priority );

		$this->_priority = ui($priority);
	}


	/**
	 * Get event priority
	 *
	 * @return int 
	 */
	public function getPriority()
	{
		return $this->_priority;
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return $this->getObjectName() ? $this->getObjectName() : '(new)';
	}
}
