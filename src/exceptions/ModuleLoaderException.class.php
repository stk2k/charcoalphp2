<?php
/**
* Exception when module loader fails
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ModuleLoaderException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $message, Exception $previous = NULL )
	{
		if ( $previous === NULL ) parent::__construct( $message ); else parent::__construct( $message, $previous );
	}
}

