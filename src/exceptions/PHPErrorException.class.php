<?php
/**
* exception in PHP runtime
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

	public function __construct( $errno, $errstr, $errfile, $errline, $prev = NULL )
	{
		parent::__construct( "PHP Runtime Error([$errno]$errstr   @$errfile($errline)", $prev );

		$this->errno = $errno;
		$this->errstr = $errstr;
		$this->errfile = $errfile;
		$this->errline = $errline;
	}
}

