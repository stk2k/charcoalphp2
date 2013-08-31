<?php
/**
* 連想配列クラス
*
* キー、値ともに型は限定しない
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_HashMap extends Charcoal_Primitive implements Iterator, ArrayAccess, Countable
{
	private $_values;

	/*
	 *	constructor
	 */
	public function __construct( array $values = NULL )
	{
		parent::__construct();

		$this->_values = $values ? $values : array();
	}

    /**
     *	unbox primitive value
     */
    public function unbox()
    {
        return $this->_value;
    }

	/**
	 *	get key list
	 */
	public function getKeys() {
		return array_keys($this->_values);
	}

	/**
	 *  check if specified key is in the list
	 */
	public function keyExists( $key )
	{
		return array_key_exists($key,$this->_values);
	}

	/**
	 *	Iterator interface: rewind() implementation
	 */
	public function rewind() {
		reset($this->_values);
	}

	/**
	 *	Iterator interface: current() implementation
	 */
	public function current() {
		$var = current($this->_values);
		return $var;
	}

	/**
	 *	Iterator interface: key() implementation
	 */
	public function key() {
		$var = key($this->_values);
		return $var;
	}

	/**
	 *	Iterator interface: next() implementation
	 */
	public function next() {
		$var = next($this->_values);
		return $var;
	}

	/**
	 *	Iterator interface: valid() implementation
	 */
	public function valid() {
		$var = $this->current() !== false;
		return $var;
	}

	/**
	 *	Check if the collection is empty
	 */
	public function isEmpty()
	{
		return count( $this->_values ) === 0;
	}

	/**
	 *	Get an element value
	 */
	public function get( $key )
	{
		return $this->offsetGet( $key );
	}

	/**
	 *	Get all values with keys
	 */
	public function getAll()
	{
		return $this->_values;
	}

	/**
	 *	update an element value
	 */
	public function set( $key, $value )
	{
		$this->offsetSet( $key, $value );
	}

	/**
	 *	Get an element value
	 */
	public function __get( $key )
	{
		return $this->offsetGet( $key );
	}

	/**
	 *	Set an element value
	 */
	public function __set( $key, $value )
	{
		$this->offsetSet( $key, $value );
	}

	/**
	 *	ArrayAccess interface : offsetGet() implementation
	 */
	public function offsetGet($offset)
	{
		if ( is_object($offset) ){
			$offset = $offset->__toString();
		}
		return isset($this->_values[ $offset ]) ? $this->_values[ $offset ] : NULL;
	}

	/**
	 *	ArrayAccess interface : offsetSet() implementation
	 */
	public function offsetSet($offset, $value)
	{
		if ( is_object($offset) ){
			$offset = $offset->__toString();
		}
		$this->_values[ $offset ] = $value;
	}

	/**
	 *	ArrayAccess interface : offsetExists() implementation
	 */
	public function offsetExists($offset)
	{
		return isset($this->_values[$offset]);
	}

	/**
	 *	ArrayAccess interface : offsetUnset() implementation
	 */
	public function offsetUnset($offset)
	{
		unset($this->_values[$offset]);
	}

	/**
	 *	Countable interface: count() implementation
	 */
	public function count()
	{
		return count( $this->_values );
	}

	/**
	 *	get key list
	 */
	public function keys()
	{
		return array_keys($this->_values);
	}

	/**
	 *	Set all array elements
	 */
	public function setArray( array $array )
	{
		$this->_values = array_merge( $this->_values, $array );
	}

	/**
	 *	Set all hashmap elements
	 */
	public function setHashMap( Charcoal_HashMap $map )
	{
		$this->_values = array_merge( $this->_values, $map->getAll() );
	}

	/**
	 *	Merge with array
	 */
	public function mergeArray( array $array, Charcoal_Boolean $overwrite = NULL )
	{
		$overwrite = $overwrite ? $overwrite->isTrue() : TRUE;

		foreach( $array as $key => $value ){
			if ( !$this->keyExists($key) || $overwrite ){
				$this->offsetSet( $key, $value );
			}
		}
	}

	/**
	 *	Merge with hashmap
	 */
	public function mergeHashMap( Charcoal_HashMap $obj, Charcoal_Boolean $overwrite = NULL )
	{
		$overwrite = $overwrite ? $overwrite->isTrue() : TRUE;

		foreach( $obj as $key => $value ){
			if ( !$this->keyExists($key) || $overwrite ){
				$this->offsetSet( $key, $value );
			}
		}
	}

	/**
	 * convert to array
	 * 
	 * @return array
	 */
	public function toArray()
	{
		if ( is_array($this->_values) ){
			return $this->_values;
		}
		return array_diff( $this->_values, array() );
	}

	/**
	 *	make string glued by a delimiter
	 */
	public function implodeAssoc( $glue = ',' )
	{
		return Charcoal_System::implodeAssoc( $glue, $this->_values );
	}

	/**
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return $this->implodeAssoc();
	}

}

