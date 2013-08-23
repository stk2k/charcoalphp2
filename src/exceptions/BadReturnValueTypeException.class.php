<?php
/**
* 戻り値タイプ異常例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_BadReturnValueTypeException extends Charcoal_RuntimeException
{
	public function __construct( $return_value, Charcoal_String $expected_type, Exception $previous = NULL )
	{
		$msg = "Bad return value type[" . gettype($return_value) . "]. [$expected_type] is expected.";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}
}


return __FILE__;