<?php
/**
* Exception when invalid image format is detected
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_UnsupportedImageFormatException extends Charcoal_RuntimeException
{
	public function __construct( $url, $prev = NULL )
	{
		parent::__construct( "[$url] is not suitable for URI", $prev );
	}
}

