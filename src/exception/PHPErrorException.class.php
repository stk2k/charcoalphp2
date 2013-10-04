<?php
/**
* exception in PHP runtime
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_PHPErrorException extends Charcoal_LogicException
{
	public $errno;
	public $errstr;
	public $errfile;
	public $errline;

	public function __construct( $errno, $errstr, $errfile, $errline, $prev = NULL )
	{
		$errno = Charcoal_System::phpErrorString( $errno );
		parent::__construct( "PHP Error([$errno]$errstr [file]$errfile [line]$errline", $prev );

		$this->errno   = $errno;
		$this->errstr  = $errstr;
		$this->errfile = $errfile;
		$this->errline = $errline;
	}
}

