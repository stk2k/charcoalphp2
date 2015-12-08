<?php
/**
* exception caused by failure in manipulating stack
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_StackEmptyException extends Charcoal_RuntimeException
{
    public function __construct( $stack, $prev = NULL )
    {
        parent::__construct( "stack is empty: $stack", $prev );
    }

}

