<?php
/**
* Exception caused by no implementation is found about specified interface
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_InterfaceImplementException extends Charcoal_RuntimeException
{
    public function __construct( $object, $interface_name, $prev = NULL )
    {
        $class_name = $object instanceof Charcoal_Object ? $object->getClassName() : get_class( $object );
        $object_hash = $object instanceof Charcoal_Object ? $object->hash() : spl_object_hash( $object );

        $object_str = '[' . $class_name . '] id=' . $object_hash;

        parent::__construct( "Object[$object_str] must implement interface[$interface_name]", $prev );
    }

}


