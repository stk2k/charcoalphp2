<?php
/**
* Smartyコンパイル例外
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_SmartyCompileException extends Charcoal_RuntimeException
{
	public $errno;
	public $errstr;
	public $errfile;
	public $errline;

	public function __construct( $errno, $errstr, $errfile, $errline, Exception $previous = NULL )
	{
		$msg  = "PHP Runtime Error";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );

		$this->errno = $errno;
		$this->errstr = $errstr;
		$this->errfile = $errfile;
		$this->errline = $errline;
	}
}

return __FILE__;