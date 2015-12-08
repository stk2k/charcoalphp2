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

class Charcoal_CacheDriverException extends Charcoal_RuntimeException
{
    public function __construct( $driver_type, $message, $prev = NULL )
    {
        parent::__construct( "driver_type=[$driver_type] message=[$message]", $prev );
    }

}

