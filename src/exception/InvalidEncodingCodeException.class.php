<?php
/**
* exception caused by invalid encoding type
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_InvalidEncodingCodeException extends Charcoal_RuntimeException
{
    public function __construct( $encoding, $prev = NULL )
    {
         parent::__construct( "Invalid encoding type: $encoding", $prev );
    }
}


