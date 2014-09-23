<?php
/**
* Cookie Class
*
* PHP version 5
*
* @package    class.core
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_CookieReader extends Charcoal_Object
{
	private $values;	// array

	/*
	 *	Construct object
	 */
	public function __construct()
	{
		parent::__construct();

		$this->values = new Charcoal_HashMap();

		// store client cookies
		if ( $_COOKIE && is_array($_COOKIE) ){
			foreach( $_COOKIE as $key => $value ){
				$this->values[$key] = $value;
			}
		}
	}

	/*
	 * Get all cookie values
	 */
	public function getAll()
	{
		return $this->values;
	}

	/*
	 * Get cookie value keys
	 */
	public function getKeys()
	{
		return $this->values->getKeys();
	}

	/*
	 * Get cookie value
	 */
	public function getValue( $name )
	{
		return $this->values->get( $name );
	}

}


