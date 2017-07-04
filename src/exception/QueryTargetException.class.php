<?php
/**
* Exception while query target parsing
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_QueryTargetException extends Charcoal_RuntimeException
{
    /**
     * Charcoal_QueryTargetException constructor.
     * @param string|Charcoal_String $expression
     * @param null $prev
     */
    public function __construct( $expression, $prev = NULL )
    {
        parent::__construct( "Illegal query target format: {$expression}", $prev );
    }

}

