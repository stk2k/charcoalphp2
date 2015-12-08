<?php
/**
*  Exception when session handler is something wrong
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_SessionHandlerException extends Charcoal_RuntimeException
{
    public function __construct( $message, $prev = NULL )
    {
        parent::__construct( $message, $prev );
    }
}


