<?php
/**
* exception caused by configuration of class loader
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ClassLoaderConfigException extends Charcoal_ConfigException
{
	public function __construct( $object_path, $entry, $message = NULL, $prev = NULL )
	{
		parent::__construct( "[object_path]$object_path [entry]$entry [message]$message", $prev );
	}
}

