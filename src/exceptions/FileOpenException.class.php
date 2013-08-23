<?php
/**
* ファイルオープン例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_FileOpenException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_File $file, Exception $previous = NULL )
	{
		$msg = 'file[' . $file->getPath() . "] could not be opened.";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}
}


return __FILE__;