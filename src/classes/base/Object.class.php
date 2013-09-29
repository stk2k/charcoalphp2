<?php
/**
* Most basic class in charcoalphp
*
* PHP version 5
*
* @package    classes.base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Object
{
	private $_object_hash;
	private static $id_master;

	/**
	 *	Constructor
	 */
	public function __construct()
	{
		$class_name = get_class($this);

//		static $id_master = 0;
//		$this->_object_hash = ++$id_master;

		$cnt = isset(self::$id_master[$class_name]) ? self::$id_master[$class_name] : 0;
		$this->_object_hash = ++$cnt;
		self::$id_master[$class_name] = $cnt;
	}

	/**
	 *	
	 */

	public static function dump()
	{
		echo nl2br(print_r(self::$id_master,true));
	}


	/**
	 *	make hash code of this object
	 *
	 * @return string   unique string of this object
	 */
	public function hash()
	{
		return $this->_object_hash;
	}

	/**
	 *	test equal objects
	 *
	 * @return boolean   returns TRUE if this object is regarded as same object to target object.
	 */
	public function equals( $object )
	{
		if ( !($object instanceof Charcoal_Object) ){
			return FALSE;
		}
		return $this->_object_hash === $object->_object_hash;
	}

	/**
	 *  Check if an object implements or extends target
	 *
	 * @return boolean   returns TRUE if this object implements interface, or derived from target class.
	 */
	public function isInstanceOf( $target )
	{
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
		return new Charcoal_Class( get_class( $this ) );
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();	// __toString() must return string type only!
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return '[class=' . get_class($this) . ' hash=' . $this->_object_hash . ']';
	}
}

