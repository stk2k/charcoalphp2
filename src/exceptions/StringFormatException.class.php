<?php
/**
* 文字列書式例外
*
* [詳細]
* ・文字列でなければならない箇所で文字列以外の書式の値が渡された
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_StringFormatException extends Charcoal_RuntimeException
{
	public function __construct( $var_value, Charcoal_String $message = NULL, Exception $previous = NULL )
	{
		$msg  = "[var value]$var_value";
		$msg .= "[message] must be a STRING value. $message";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}

return __FILE__;