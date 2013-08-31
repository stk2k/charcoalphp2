<?php
/**
* 想定外のランモード例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_CreateObjectException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_ObjectPath $obj_path, Charcoal_String $type_name, Exception $previous = NULL )
	{
		$msg = "Creating Charcoal Object Failed. [obj_path]$obj_path [type_name]$type_name";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}

