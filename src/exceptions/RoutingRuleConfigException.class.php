<?php
/**
* exception caused by configuration of routing rule
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_RoutingRuleConfigException extends Charcoal_ConfigException
{
	public function __construct( $entry, $message = NULL, Exception $previous = NULL )
	{
		parent::__construct( "[entry]$entry [message]$message", $prev );
	}
}

