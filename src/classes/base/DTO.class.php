<?php
/**
* Data transfer object(DTO)
*
* PHP version 5
*
* @package    classes.base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_DTO extends Charcoal_HashMap implements Iterator, ArrayAccess
{
	/**
	 *	constructor
	 */
	public function __construct( $values = array() )
	{
		parent::__construct( $values );

		foreach( $values as $key => $value ){
			if ( property_exists($this, $key) ){
				$this->$key = $value;
			}
		}
	}

	/**
	 *	Iterator interface: current() implementation
	 */
	public function current() {
		$key = key($this->values);
		if ( property_exists($this,$key) ){
			$var = $this->$key;
		}
		else{
			$var = current($this->values);
		}
		return $var;
	}

	/**
	 *	ArrayAccess interface : offsetGet() implementation
	 */
	public function offsetGet($offset)
	{
		if ( is_object($offset) ){
			$offset = $offset->__toString();
		}
		if ( property_exists($this,$offset) ){
			return $this->$offset;
		}
		return isset($this->values[ $offset ]) ? $this->values[ $offset ] : NULL;
	}

	/**
	 *	ArrayAccess interface : offsetSet() implementation
	 */
	public function offsetSet($offset, $value)
	{
		if ( is_object($offset) ){
			$offset = $offset->__toString();
		}
		$this->values[ $offset ] = $value;
		if ( property_exists($this,$offset) ){
			$this->$offset = $value;
		}
	}

	/**
	 *	ArrayAccess interface : offsetExists() implementation
	 */
	public function offsetExists($offset)
	{
		if ( property_exists($this,$offset) ){
			return TRUE;
		}
		return isset($this->values[$offset]);
	}

	/**
	 *	ArrayAccess interface : offsetUnset() implementation
	 */
	public function offsetUnset($offset)
	{
		unset($this->values[$offset]);
		if ( property_exists($this,$offset) ){
			$this->$offset = NULL;
		}
	}
}