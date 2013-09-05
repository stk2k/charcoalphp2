<?php
/**
* exception caused by not suitable for integer object
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_NonIntegerException extends Charcoal_RuntimeException
{
	public function __construct( $value, $prev = NULL )
	{
		parent::__construct( "can't convert to integer object: $value", $prev );
	}
}


