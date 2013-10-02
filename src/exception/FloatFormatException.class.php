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

class Charcoal_FloatFormatException extends Charcoal_RuntimeException
{
	public function __construct( $value, $prev = NULL )
	{
		$value = Charcoal_System::toString( $value );
		parent::__construct( "must be an FLOAT value: $value(" . gettype($value) . ")", $prev );
	}
}
