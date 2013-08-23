<?php
/**
* プロファイルロード例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ProfileLoadingException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_File $config_file, Charcoal_String $profile_name, Exception $previous = NULL )
	{
		$msg = "Profile loading failed. config_file=[$config_file] profile_name=[$profile_name]";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}

return __FILE__;