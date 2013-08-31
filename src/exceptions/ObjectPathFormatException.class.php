<?php
/**
* オブジェクトパス書式例外
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ObjectPathFormatException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $object_path, Charcoal_String $message = NULL, Exception $prev = NULL )
	{
		$msg  = " [object_path]" . $object_path . " [message]$message";

		if ( $prev ) parent::__construct( s($msg), $prev ); else parent::__construct( s($msg) );
	}
}

