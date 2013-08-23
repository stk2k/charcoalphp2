<?php
/**
* パラメータ例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ParameterException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $parameter_name = NULL, Charcoal_String $value = NULL, Charcoal_String $message = NULL, Exception $previous = NULL )
	{
		$msg = "";
		if ( $parameter_name && is_string($parameter_name) ){
			$msg .= "[parameter name]$parameter_name";
		}
		if ( $value && is_string($value) ){
			$msg .= " [value]$value";
		}
		if ( $message && is_string($message) ){
			$msg .= " [message]$message";
		}

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}

return __FILE__;