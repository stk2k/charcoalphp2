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

class Charcoal_ComponentLoadingException extends Charcoal_RuntimeException
{
    public function __construct( $component_name, $prev = NULL )
    {
        parent::__construct( "component loading failed: $component_name", $prev );
    }
}

