<?php
/**
* PHPソースパーサ例外
*
* PHP version 5
*
* @version    0.1
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
* @license    http://www.opensource.org/licenses/mit-license.php MIT License
*/

class Charcoal_PhpSourceParserException extends Charcoal_RuntimeException 
{
	public function __construct( Charcoal_Integer $err_code, Charcoal_String $message, Exception $previous = NULL )
	{
		$msg = "[code]$err_code [message]$message";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}

return __FILE__;