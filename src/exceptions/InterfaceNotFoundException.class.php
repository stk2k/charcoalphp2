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
	public function __construct( Charcoal_String $interface_name, Exception $prev = NULL )
	{
		$msg = "Interface not found: interface_name=[$interface_name]";

		if ( $prev ) parent::__construct( s($msg), $prev ); else parent::__construct( s($msg) );
	}

}

return __FILE__;
