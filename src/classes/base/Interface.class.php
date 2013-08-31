<?php
/**
* Interface information class
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Interface extends Charcoal_Object
{
	private $interface_name;

	/*
	 *	Constructor
	 */
	public function __construct( Charcoal_String $interface_name )
	{
		parent::__construct();

		if ( !interface_exists($interface_name) ){
			_throw( new Charcoal_InterfaceNotFoundException( $interface_name ) );
		}

		$this->interface_name = $interface_name;
	}

	/*
	 *  Get interface name
	 *
	 * @return string    interface name
	 */
	public function getInterfaceName()
	{
		return $this->interface_name;
	}

	/*
	 *  Check if an object implements this interface
	 *
	 */
	public function checkImplements( Charcoal_Object $object )
	{
		$interface_name = us($this->interface_name);

		if ( $interface_name && !($object instanceof $interface_name) ){
			// Invoke Exception
			_throw( new Charcoal_InterfaceImplementException( $object, s($interface_name) ) );
		}
	}


	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return __CLASS__ . '[' . $this->interface_name . ']';
	}
}

