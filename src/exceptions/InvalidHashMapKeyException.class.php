<?php
/**
* Invali hashmap key
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_InvalidHashMapKeyException extends Charcoal_RuntimeException
{
	public function __construct( $key, Exception $previous = NULL )
	{
		$msg = "Invalid hash map key[" . gettype($key) . "]";

		if ( $previous ) parent::__construct( s($msg), $previous ); else parent::__construct( s($msg) );
	}
}


return __FILE__;