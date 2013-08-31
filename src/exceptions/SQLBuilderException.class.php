<?php
/**
* SQLビルダ例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_SQLBuilderException extends Charcoal_DBException
{
	public function __construct( Charcoal_String $message = NULL, Exception $previous = NULL )
	{
		if ( $previous ) parent::__construct( s($message), $previous ); else parent::__construct( s($message) );
	}
}


