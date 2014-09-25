<?php
/**
* exception caused by configuration of routing rule
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_RoutingRuleConfigException extends Charcoal_ConfigException
{
	public function __construct( $entry, $message = NULL, $prev = NULL )
	{
		parent::__construct( "[entry]$entry [message]$message", $prev );
	}
}

