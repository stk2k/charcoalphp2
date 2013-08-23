<?php
/**
* エンコーディング変換例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_EncodingConverterException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $message, Exception $previous = NULL )
	{
		$msg = "[message]$message";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}

return __FILE__;