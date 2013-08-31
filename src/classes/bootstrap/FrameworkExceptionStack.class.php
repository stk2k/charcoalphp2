<?php
/**
* Framework Exception Stack
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_FrameworkExceptionStack extends Charcoal_Object
{
	var $_stack;

	/*
	 *	Construct object
	 *
	 */
	public function __construct()
	{
		$this->_stack = array();
	}

	/*
	 *  Singleton instance
	 */
	public static function getInstance()
	{
		static $singleton_;
		if ( $singleton_ == null ){
			$singleton_ = new Charcoal_FrameworkExceptionStack();
		}
		return $singleton_;
	}

	/**
	 *  Return if stack is empty
	 *
	 * @return Charcoal_Boolean TRUE if stack is empty, otherwise FALSE.
	 */
	public function isEmpty()
	{
		return count($this->_stack) === 0;
	}

	/*
	 *  Add exception
	 *
	 * @param Exception Exception to add
	 */
	public static function push( Exception $e )
	{
		// Get singleton instace
		$ins = self::getInstance();

		// Just add exception
		array_push( $ins->_stack, $e );
	}

	/**
	 *  Get top exception
	 *
	 * @return Charcoal_String
	 */
	public static function pop()
	{
		// Get singleton instace
		$ins = self::getInstance();

		return array_pop( $ins->_stack );
	}

	/**
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return implode( ",", $this->_stack );
	}
}
