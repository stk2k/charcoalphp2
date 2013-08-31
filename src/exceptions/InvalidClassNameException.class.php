<?php
/**
* 無効クラス名例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_InvalidClassNameException extends Charcoal_RuntimeException
{
	public function __construct( $class_name, $message = NULL, Exception $previous = NULL )
	{
		$msg = "[class_name]$class_name";
		if ( $message != NULL ){
			$msg .= " [message]$message";
		}

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}


