<?php
/**
* Interface is not found exception 
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_InvalidArgumentException extends Charcoal_RuntimeException
{
    public function __construct( $arg, $prev = NULL )
    {
        $expr = '';
        switch(gettype($arg)){
        case 'string':
        case 'integer':
        case 'float':
        case 'boolean':
            $expr = "$arg";
            break;
        case 'NULL':
            $expr = 'NULL';
            break;
        case 'unknown type':
            $expr = 'unknown type';
            break;
        case 'array':
            $expr = print_r($arg,true);
            break;
        case 'object':
            $expr = method_exists($arg,'_toString') ? "$arg" : gettype($arg) . '#' . spl_object_hash($arg);
            break;
        }

        parent::__construct( "Invalid argument: $expr", $prev );
    }

}


