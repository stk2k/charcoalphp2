<?php
/**
* exception caused by not suitable for date format
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_DateFormatException extends Charcoal_RuntimeException
{
	public function __construct( $format, $prev = NULL )
	{
		parent::__construct( "not date format: [$format]", $prev );
	}

}

