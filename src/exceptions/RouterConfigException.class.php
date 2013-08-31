<?php
/**
* Router Configuration Exception
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_RouterConfigException extends Charcoal_ConfigException
{
	public function __construct( Charcoal_String $message = NULL, Exception $previous = NULL )
	{
		if ( $previous ) parent::__construct( s($message), $previous ); else parent::__construct( s($message) );
	}
}
