<?php
/**
* Test Event
*
* PHP version 5
*
* @package    objects.events
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ExceptionEvent extends Charcoal_SystemEvent 
{
	private $exception;

	public function __construct( $exception )
	{
		$this->exception = $exception;

		parent::__construct();
	}

	/**
	 *  get exception
	 */
	public function getException()
	{
		return $this->exception;
	}

}

