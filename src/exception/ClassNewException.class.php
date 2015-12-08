<?php
/**
* exception caused by creating new instance
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_ClassNewException extends Charcoal_RuntimeException
{
    public function __construct( Charcoal_Class $klass, Charcoal_Vector $args, $prev = NULL )
    {
        $class_name = $klass->getClassName();

        parent::__construct( "[class_name]$class_name [args]$args", $prev );
    }


}
