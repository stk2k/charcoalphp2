<?php
/**
* Cookie Class
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_CookieReader extends Charcoal_Object
{
	private $_values;	// array

	/*
	 *	Construct object
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_values = array();

		// store client cookies
		if ( $_COOKIE && is_array($_COOKIE) ){
			foreach( $_COOKIE as $key => $value ){
				$this->_values[$key] = $value;
			}
		}
	}

	/*
	 * Get cookie value keys
	 */
	public function getKeys()
	{
		return $this->_value->getKeys();
	}

	/*
	 * Get cookie value
	 */
	public function getValue( $name )
	{
		$name = us($name);
		return isset($this->_values[$name]) ? $this->_values[$name] : NULL;
	}

}


