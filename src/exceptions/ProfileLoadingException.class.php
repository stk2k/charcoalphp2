<?php
/**
* exception caused by failure in loading profile
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ProfileLoadingException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_File $config_file, Charcoal_String $profile_name, $prev = NULL )
	{
		parent::__construct( "Profile loading failed. [config_file]$config_file [profile_name]$profile_name", $prev );
	}

}

