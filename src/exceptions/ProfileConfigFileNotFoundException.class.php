<?php
/**
* Exception in missing profile config file
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ProfileConfigFileNotFoundException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_File $config_file, Exception $prev = NULL )
	{
		$msg  = "Profile Config File Not Found: [$config_file].";

		if ( $prev ) parent::__construct( s($msg), $prev ); else parent::__construct( s($msg) );
	}
}


return __FILE__;