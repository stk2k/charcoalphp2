<?php
/**
* インタフェース実装例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_InterfaceImplementException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_Object $object, Charcoal_String $interface_name, Exception $previous = NULL )
	{
		$object_name = '[' . $object->getClassName() . '] id=' . $object->hash();

		$msg = "Object[$object_name] must implement interface[$interface_name]";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}

return __FILE__;
