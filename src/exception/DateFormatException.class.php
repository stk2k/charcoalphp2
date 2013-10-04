<?php
/**
* exception caused by not suitable for date format
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_DateFormatException extends Charcoal_RuntimeException
{
	public function __construct( $format, $prev = NULL )
	{
		parent::__construct( "not date format: [$format]", $prev );
	}

}

