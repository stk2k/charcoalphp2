<?php
/**
* ディレクトリパーミッション例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_DirectoryPermissionException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $path, Charcoal_String $desired_access, Exception $previous = NULL )
	{
		$msg = "[" . us($path) ."] has not permision of [" . us($access) . "]";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}
}


return __FILE__;