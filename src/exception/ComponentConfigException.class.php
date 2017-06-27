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

class Charcoal_ComponentConfigException extends Charcoal_ConfigException
{
    /**
     * Charcoal_ComponentConfigException constructor.
     *
     * @param string $component_name
     * @param string $entry
     * @param string $message
     * @param Exception $prev
     */
    public function __construct( $component_name, $entry, $message = NULL, $prev = NULL )
    {
        parent::__construct( "component($component_name) config maybe wrong: [entry]$entry [message]$message", $prev );
    }
}

