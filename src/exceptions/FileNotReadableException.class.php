<?php
/**
* Exception when file is not readable
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_FileNotReadableException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_File $file, Exception $prev = NULL )
	{
		$msg = 'File[' . $file->getPath() . "] is not readable.";

		if ( $prev ) parent::__construct( s($msg), $prev ); else parent::__construct( s($msg) );
	}
}


