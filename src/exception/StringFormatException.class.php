<?php
/**
* exception caused by not suitable for string value
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_StringFormatException extends Charcoal_RuntimeException
{
	public function __construct( $key, $prev = NULL )
	{
		parent::__construct( "must be an STRING value for key[$key]", $prev );
	}

}

