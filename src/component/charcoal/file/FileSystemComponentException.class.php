<?php
/**
* File System Component Exception
*
* PHP version 5
*
* @package    component.charcoal.file
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_FileSystemComponentException extends Charcoal_RuntimeException
{
    /***
     *  construct
     *
     * @param string|Charcoal_String $message
     * @param Exception|null $prev
     **/
    public function __construct( $message, $prev = NULL )
    {
        if ( $prev ) parent::__construct( $message, $prev ); else parent::__construct( $message );
    }
}


