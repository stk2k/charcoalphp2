<?php
/**
* Exception in operating cache
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_RegistryException extends Charcoal_RuntimeException
{
    public function __construct( $registry_type, $message, $prev = NULL )
    {
        parent::__construct( "registry_type=[$registry_type] message=[$message]", $prev );
    }

}

