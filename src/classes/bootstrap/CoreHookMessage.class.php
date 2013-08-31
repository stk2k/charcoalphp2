<?php
/**
* core hook message object
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_CoreHookMessage extends Charcoal_Object
{
	private $stage;
	private $data;

	/**
	 *	Constructor
	 */
	public function __construct( Charcoal_Integer $stage, Charcoal_Object $data = NULL )
	{
		$this->stage   = $stage;
		$this->data    = $data;
	}

	/**
	 *	Get stage
	 */
	public function getStage()
	{
		return $this->stage;
	}

	/**
	 *	Get data
	 */
	public function getData()
	{
		return $this->data;
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return "[{$this->stage}]{$this->data}";
	}
}


