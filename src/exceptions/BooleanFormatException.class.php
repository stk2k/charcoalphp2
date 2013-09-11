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
	public function __construct( $value, $prev = NULL )
	{
		$value = Charcoal_System::toString( $value );

		parent::__construct( "must be an BOOLEAN value: $value(" . gettype($value) . ")", $prev );
	}

}

