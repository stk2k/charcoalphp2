<?php
/**
* ファイルが存在しない例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_FileNotFoundException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_File $file, Exception $previous = NULL )
	{
		$msg = 'File[' . $file->getPath() . "] does not exists.";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}
}


