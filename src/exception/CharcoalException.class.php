<?php
/**
* top level exception
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_CharcoalException extends Exception
{
	private $backtrace;

	/**
	 *	Construct
	 */
	public function __construct( $message, $prev = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $message );
//		Charcoal_ParamTrait::checkException( 2, $prev );

		parent::__construct( $message, 0, $prev );

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

