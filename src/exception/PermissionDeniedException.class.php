<?php
/**
* Exception when user has not properly permission
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_PermissionDeniedException extends Charcoal_BusinessException
{
    public function __construct( $prev = NULL )
    {
        parent::__construct( "Permission denied", $prev );
    }
}

