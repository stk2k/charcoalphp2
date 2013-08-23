<?php
/**
* Most basic class in charcoalphp
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Object
{
	/**
	 *	Constructor
	 */
	public function __construct()
	{
	}

	/**
	 *	make hash code of this object
	 *
	 * @return string   unique string of this object
	 */
	public function hash()
	{
		return object_hash($this);
	}

	/**
	 *	test equal objects
	 *
	 * @return boolean   returns TRUE if this object is regarded as same object to target object.
	 */
	public function equals( Charcoal_Object $object )
	{
		return $this->hash() === $object->hash();
	}

	/**
	 *  Check if an object implements or extends target
	 *
	 * @return boolean   returns TRUE if this object implements interface, or derived from target class.
	 */
	public function isInstanceOf( Charcoal_String $target )
	{
		$target = us( $target );
		return $this instanceof $target;
	}

	/**
	 * Get class name
	 *
	 * @return string   class name
	 */
	public function getClassName()
	{
		return get_class($this);
	}

	/**
	 * Get class 
	 */
	public function getClass()
	{
		return new Charcoal_Class( s($this->getClassName()) );
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public final function __toString()
	{
		return us($this->toString());	// __toString() must return string type only!
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return '[class=' . get_class($this) . ' hash=' . $this->hash() . ']';
	}
}
return __FILE__;
