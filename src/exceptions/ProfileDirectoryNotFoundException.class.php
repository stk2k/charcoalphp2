<?php
/**
* バリデータ設定例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ProfileDirectoryNotFoundException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_File $profile_dir )
	{
		$msg = "Profile directory not found: [$profile_dir]";

		parent::__construct( s($msg) );
	}
}


return __FILE__;