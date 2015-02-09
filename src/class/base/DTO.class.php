<?php
/**
* Data transfer object(DTO)
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_DTO extends Charcoal_Object implements IteratorAggregate, ArrayAccess
{
	/**
	 *	constructor
	 */
	public function __construct( $values = array() )
	{
		parent::__construct( $values );

		foreach( $values as $key => $value ){
//			if ( property_exists($this, $key) ){
				$this->$key = $value;
//			}
		}
	}

	/**
	 *	get all properties
	 */
	public function getAll() {
		return get_object_vars( $this );
	}

	/**
	 *	IteratorAggregate interface : getIterator() implementation
	 */
	public function getIterator() {
        return new ArrayIterator(get_object_vars($this));
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
		return NULL;
	}

	/**
	 *	ArrayAccess interface : offsetSet() implementation
	 */
	public function offsetSet($offset, $value)
	{
		if ( is_object($offset) ){
			$offset = $offset->__toString();
		}
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
		return FALSE;
	}

	/**
	 *	ArrayAccess interface : offsetUnset() implementation
	 */
	public function offsetUnset($offset)
	{
		if ( property_exists($this,$offset) ){
			$this->$offset = NULL;
		}
	}

	/**
	 *	Set all array elements
	 *	
	 *	@param array $array   array data to set
	 */
	public function setArray( $array )
	{
//		Charcoal_ParamTrait::checkRawArray( 1, $array );

		foreach ( $array as $key => $value ) {
			if ( property_exists($this, $key) ){
				$this->offsetSet( $key, $value );
			}
		}
	}

	/**
	 *	Set all hashmap elements
	 *	
	 *	@param array $array   hashmap data to set
	 */
	public function setHashMap( $map )
	{
//		Charcoal_ParamTrait::checkHashMap( 1, $map );

		foreach ( $map as $key => $value ) {
			if ( property_exists($this, $key) ){
				$this->offsetSet( $key, $value );
			}
		}
	}

}