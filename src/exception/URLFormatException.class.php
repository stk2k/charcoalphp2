<?php
/**
* Exception when string format is not match for URI
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_URLFormatException extends Charcoal_RuntimeException
{
    public function __construct( $url, $prev = NULL )
    {
        parent::__construct( "[$url] is not suitable for URI", $prev );
    }
}

