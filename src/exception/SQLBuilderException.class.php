<?php
/**
* exception caused by failure in SQL builder
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_SQLBuilderException extends Charcoal_DBException
{
	public function __construct( $message, $prev = NULL )
	{
		parent::__construct( "SQL builder failed: $message", $prev );
	}
}


