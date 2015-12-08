<?php
/**
* exception caused by syntax error in routing rule
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_RoutingRuleSyntaxErrorException extends Charcoal_ConfigException
{
    public function __construct( $rule, $message = NULL, $prev = NULL )
    {
        parent::__construct( "[rule]$rule [message]$message", $prev );
    }
}

