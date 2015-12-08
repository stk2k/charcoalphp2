<?php
/**
* Exception caused by failure in manipulating image file
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ImageGetSizeException extends Charcoal_RuntimeException
{
    public function __construct( Charcoal_File $file, $prev = NULL )
    {
        parent::__construct( "getimagesize() failed. path=[$file]", $prev );
    }

}

