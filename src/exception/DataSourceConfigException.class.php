<?php
/**
* exception caused by configuration of component
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_DataSourceConfigException extends Charcoal_ConfigException
{
    public function __construct( $entry, $message = NULL, $prev = NULL )
    {
        parent::__construct( "data dource config maybe wrong: [entry]$entry [message]$message", $prev );
    }
}

