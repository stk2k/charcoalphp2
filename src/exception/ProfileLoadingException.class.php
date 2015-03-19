<?php
/**
* exception caused by failure in loading profile
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ProfileLoadingException extends Charcoal_RuntimeException
{
	public function __construct( $config_file, $profile_name, $prev = NULL )
	{
//		Charcoal_ParamTrait::validateString( 1, $config_file );
//		Charcoal_ParamTrait::validateString( 2, $profile_name );

		parent::__construct( "Profile loading failed. [config_file]$config_file [profile_name]$profile_name", $prev );
	}

}

