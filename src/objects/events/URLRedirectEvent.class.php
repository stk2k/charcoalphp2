<?php
/**
* URL Redirect Event
*
* PHP version 5
*
* @package    objects.events
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_URLRedirectEvent extends Charcoal_SystemEvent 
{
	private $_url;

	/**
	 *	Construct object
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 *	Set URL
	 *
	 * @param string  URL
	 */
	public function setURL( Charcoal_String $url )
	{
		$this->_url = us($url);
	}

	/**
	 *	Get URL
	 *
	 * @return string
	 */
	public function getURL()
	{
		return $this->_url;
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return '[class=' . get_class($this) . ' hash=' . $this->hash() . ' url=' . $this->_url . ' priority=' . $this->getPriority() . ']';
	}
}

