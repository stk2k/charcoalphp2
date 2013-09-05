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
	public function __construct( $config_file, $prev = NULL )
	{
		parent::__construct( "Profile Config File Not Found: [$config_file].", $prev );
	}
}


