<?php
/**
* バリデータ設定例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_InvalidEncodingCodeException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $code, Exception $previous = NULL )
	{
		$msg = "Invalid encoding code[" . us($code) . "]";

		if ( $previous ) parent::__construct( s($msg), $previous ); else parent::__construct( s($msg) );
	}
}


