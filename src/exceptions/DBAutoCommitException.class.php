<?php
/**
* DB例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_DBAutoCommitException extends Charcoal_DBException
{
	public function __construct( Charcoal_String $message, Exception $previous = NULL )
	{
		if ( $previous === NULL ) parent::__construct( $message ); else parent::__construct( $message, $previous );
	}

}
return __FILE__;
