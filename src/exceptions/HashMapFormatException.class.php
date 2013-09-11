<?php
/**
* exception caused by not suitable for hash map value
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_HashMapFormatException extends Charcoal_RuntimeException
{
	public function __construct( $value, $prev = NULL )
	{
		$value = Charcoal_System::toString( $value );

		parent::__construct( "must be an HASHMAP value: $value(" . gettype($value) . ")", $prev );
	}

}

