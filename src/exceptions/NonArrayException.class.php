<?php
/**
* 非配列例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_NonArrayException extends Charcoal_RuntimeException
{
	public function __construct( $object = NULL, Exception $previous = NULL )
	{
		$msg = "";
		if ( $object != NULL ){
			$msg .= " [object type]" . gettype($object);
		}

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}


return __FILE__;