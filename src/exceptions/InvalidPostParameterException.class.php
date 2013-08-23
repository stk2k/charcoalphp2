<?php
/**
* 不正なPOSTパラメータ例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_InvalidPostParameterException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $key, $value, Exception $previous = NULL )
	{
		$msg = "[key]$key [value]$value";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}
}


return __FILE__;