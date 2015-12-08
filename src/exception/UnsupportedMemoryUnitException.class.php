<?php
/**
* Exception when invalid memory unit is detected
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_UnsupportedMemoryUnitException extends Charcoal_RuntimeException
{
    public function __construct( $unit, $prev = NULL )
    {
        parent::__construct( "[$unit] is not supported", $prev );
    }
}

