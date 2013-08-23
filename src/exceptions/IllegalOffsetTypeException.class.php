<?php
/**
* 配列キー例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_IllegalOffsetTypeException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $key, Charcoal_String $type, Exception $previous = NULL )
	{
		$msg = "[key]$key [type]$type";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}
}


return __FILE__;