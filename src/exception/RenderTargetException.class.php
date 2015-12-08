<?php
/**
* Exception caused by failure in rendering target
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2014 stk2k, sazysoft
*/

class Charcoal_RenderTargetException extends Charcoal_RuntimeException
{
    public function __construct( $reason = NULL, $prev = NULL )
    {
        parent::__construct( "rendering target failed: $reason", $prev );
    }
}


