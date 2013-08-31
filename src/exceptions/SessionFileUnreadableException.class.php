<?php
/**
* セッションファイル読み取り例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_SessionFileUnreadableException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_File $file, Charcoal_String $message = NULL, Exception $previous = NULL )
	{
		$msg = "[file]$file";
		if ( $message ){
			$msg .= '[message]' . us($message);
		}

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}
}


