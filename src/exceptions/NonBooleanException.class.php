<?php
/**
* 非ブール値例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_NonBooleanException extends Charcoal_RuntimeException
{
	public function __construct( $object = NULL, Exception $previous = NULL )
	{
		$msg = "Boolean value is expected.";
		if ( $object != NULL ){
			$msg .= " [object type]" . gettype($object);
		}

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}


return __FILE__;