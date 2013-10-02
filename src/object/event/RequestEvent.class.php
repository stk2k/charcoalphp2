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

class Charcoal_RequestEvent extends Charcoal_SystemEvent 
{
	private $request;

	/**
	 *	constructor
	 */
	public function __construct( Charcoal_IRequest $request )
	{
		parent::__construct();

		$this->request = $request;
	}

	/**
	 *	get requeet object
	 */
	public function getRequest()
	{
		return $this->request;
	}
}

