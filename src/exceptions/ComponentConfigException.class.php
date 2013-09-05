<?php
/**
* exception caused by configuration of component
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ComponentConfigException extends Charcoal_ConfigException
{
	public function __construct( $entry, $message = NULL, Exception $previous = NULL )
	{
		parent::__construct( "[entry]$entry [message]$message", $prev );
	}
}

