<?php
/**
* アノテーション例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_AnnotaionException extends Charcoal_RuntimeException
{
    public function __construct( $class_name, $field_name, $annotaion_name, $message, $prev = NULL )
    {
        $msg = "[class name] $class_name [field name] $field_name [annotaion name] $annotaion_name";
        if ( $message ){
            $msg .= " [message] $message";
        }

        parent::__construct( $msg, $prev );
    }

}

