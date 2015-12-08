<?php
/**
* Exception when decoding to json failed
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_JsonDecodingException extends Charcoal_RuntimeException
{
    public function __construct( $src, $prev = NULL )
    {
        parent::__construct( "Unable to decode string: $src", $prev );
    }
}

