<?php
/**
* exception caused by failure in finding profile directory
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ProfileDirectoryNotFoundException extends Charcoal_RuntimeException
{
	public function __construct( $profile_dir, $prev = NULL )
	{
		parent::__construct( "Profile directory not found: [$profile_dir]", $prev );
	}
}


