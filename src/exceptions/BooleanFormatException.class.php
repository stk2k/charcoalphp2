<?php
/**
* exception caused by not suitable for boolean value
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_BooleanFormatException extends Charcoal_RuntimeException
{
	public function __construct( $key, $prev = NULL )
	{
		parent::__construct( "must be an BOOLEAN value for key[$key]", $prev );
	}

}

