<?php
/**
* PHP実行時例外
*
* [詳細]
* ・文法例外など、error_handlerで補足された場合の例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_PHPErrorException extends Charcoal_RuntimeException
{
	public $errno;
	public $errstr;
	public $errfile;
	public $errline;

	public function __construct( $errno, $errstr, $errfile, $errline, Exception $previous = NULL )
	{
		$msg  = "PHP Runtime Error([$errno]$errstr   @$errfile($errline)";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );

		$this->errno = $errno;
		$this->errstr = $errstr;
		$this->errfile = $errfile;
		$this->errline = $errline;
	}
}

return __FILE__;