<?php
/**
* top level exception
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_CharcoalException extends Exception
{
	private $backtrace;

	/**
	 *	Construct
	 */
	public function __construct( Charcoal_String $message, Exception $previous = NULL )
	{
		parent::__construct( us($message), 0, $previous );

		$this->backtrace = debug_backtrace();
	}

	/*
	 *	get back trace
	 */
	public function getBackTrace()
	{
		return $this->backtrace;
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return get_class($this) . '(' . $this->getMessage() . ')';
	}
}
return __FILE__;
