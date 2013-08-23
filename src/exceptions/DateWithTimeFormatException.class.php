<?php
/**
* 日時値書式例外
*
* [詳細]
* ・日時値でなければならない箇所で日時値以外の書式の値が渡された
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_DateWithTimeFormatException extends Charcoal_RuntimeException
{
	public function __construct( $var_value, Charcoal_String $message = NULL, Exception $previous = NULL )
	{
		$msg  = "[var value]$var_value";
		$msg .= "[message] must be a DATETIME value. $message";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}
return __FILE__;
