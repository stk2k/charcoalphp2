<?php
/**
* Exception when an insecure operation has exeuted
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_SecurityFaultException extends Charcoal_BusinessException
{
    public function __construct( $prev = NULL )
    {
        parent::__construct( "Insecure operation has exeuted", $prev );
    }
}

