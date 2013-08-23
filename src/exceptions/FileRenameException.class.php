<?php
/**
* ファイル出力例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_FileRenameException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_File $old_file, Charcoal_File $new_file, Exception $previous = NULL )
	{
		$msg = "Rename file[{$old_file}] to [{$new_file}] failed.";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}
}


return __FILE__;