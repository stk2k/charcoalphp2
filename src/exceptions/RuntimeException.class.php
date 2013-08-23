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

class Charcoal_RuntimeException extends Charcoal_CharcoalException
{
	public function __construct( Charcoal_String $message, Exception $prev = NULL )
	{
		if ( $prev ) parent::__construct( $message, $prev ); else parent::__construct( $message );
	}

}
return __FILE__;
