<?php
/**
* exception caused by not suitable for string object
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_NonStringException extends Charcoal_RuntimeException
{
	public function __construct( $value, $prev = NULL )
	{
		parent::__construct( "can't convert to string object: $value", $prev );
	}

}


