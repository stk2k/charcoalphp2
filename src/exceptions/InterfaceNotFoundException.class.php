<?php
/**
* Interface is not found exception 
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_InterfaceNotFoundException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $interface_name, $prev = NULL )
	{
		parent::__construct( "Interface not found: interface_name=[$interface_name]", $prev );
	}

}


