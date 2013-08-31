<?php
/**
* Exception in operating cache
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_CacheDriverException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $driver_type, Charcoal_String $message, Exception $prev = NULL )
	{
		$msg = "driver_type=[$driver_type] message=[$message]";

		if ( $prev ) parent::__construct( s($msg), $prev ); else parent::__construct( s($msg) );
	}

}

