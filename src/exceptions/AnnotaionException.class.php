<?php
/**
* アノテーション例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_AnnotaionException extends Charcoal_RuntimeException
{
	public function __construct( $field_name, $annotaion_name, $message, Exception $previous = NULL )
	{
		$msg = "[field name] $field_name";
		$msg .= "[annotaion name] $annotaion_name";
		if ( $message ){
			$msg .= " [message] $message";
		}

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}

