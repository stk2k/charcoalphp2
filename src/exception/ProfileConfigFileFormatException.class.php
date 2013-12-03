<?php
/**
* Exception when profile config file has some format errors
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ProfileConfigFileFormatException extends Charcoal_RuntimeException
{
	public function __construct( $config_file, $prev = NULL )
	{
		parent::__construct( "Profile config file has some format errors: [$config_file].", $prev );
	}
}


