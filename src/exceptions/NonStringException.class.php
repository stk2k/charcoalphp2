<?php
/**
* 非文字列例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_NonStringException extends Charcoal_RuntimeException
{
	public function __construct( $value = NULL, Exception $previous = NULL  )
	{
		$msg = "";
		if ( $value != NULL ){
			$msg .= " [value]" . strval($value);
			$msg .= " [object type]" . gettype($value);
			if ( is_object($value) ){
				$msg .= " [class type]" . get_class($value);
			}
		}
		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}


return __FILE__;