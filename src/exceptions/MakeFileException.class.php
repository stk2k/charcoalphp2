<?php
/**
* ファイル作成例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_MakeFileException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $path, Exception $previous = NULL )
	{
		$msg = "making file failed: path=[$path]";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}
}


return __FILE__;