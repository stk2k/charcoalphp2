<?php
/**
* ファイルシステム例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_FileSystemException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $operation, Charcoal_String $reason = NULL, Exception $previous = NULL )
	{
		$msg = " [operation]$operation";
		if ( $reason ){
			$msg .= "  [reason]$reason";
		}

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}
}


