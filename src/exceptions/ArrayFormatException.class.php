<?php
/**
* 配列書式例外
*
* [詳細]
* ・配列でなければならない箇所で配列以外の書式の値が渡された
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ArrayFormatException extends Charcoal_RuntimeException
{
	public function __construct( $var_value, Charcoal_String $message = NULL, Exception $previous = NULL )
	{
		$msg  = "[var value]$var_value";
		$msg .= "[message] must be an ARRAY value. $message";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}

return __FILE__;