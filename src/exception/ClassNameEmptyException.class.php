<?php
/**
* exception caused by empty class name for object
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_ClassNameEmptyException extends Charcoal_RuntimeException
{
    public function __construct( $object_name, $prev = NULL )
    {
        parent::__construct( "Class name is required: object_name=[$object_name]", $prev );
    }


}
