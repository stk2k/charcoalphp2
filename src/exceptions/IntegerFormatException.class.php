<?php
/**
* exception caused by not suitable for float value
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_IntegerFormatException extends Charcoal_RuntimeException
{
	public function __construct( $key, $prev = NULL )
	{
		parent::__construct( "must be an INTEGER value for key[$key]", $prev );
	}
}

