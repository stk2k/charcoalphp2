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
	public function __construct( $values = NULL )
	{
		parent::__construct();

		$this->_values = $values ? um($values) : array();
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
	 *	
	 *	@return bool        TRUE if this collection has no elements, FALSE otherwise
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
	 *
	 * @return array
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
	 *	
	 *	@param array $array   array data to set
	 */
	public function setArray( $data )
	{
//		Charcoal_ParamTrait::checkRawArray( 1, $data );

		$this->_values = $this->_values ? array_merge( $this->_values, $data ) : $data;
	}

	/**
	 *	Set all hashmap elements
	 *	
	 *	@param array $array   hashmap data to set
	 */
	public function setHashMap( $data )
	{
//		Charcoal_ParamTrait::checkHashMap( 1, $data );

		$this->_values = $this->_values ? array_merge( $this->_values, $map->getAll() ) : $data;
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
	 *  Get element value as string
	 *
	 * @param string $key             Key string to get
	 * @param string $default_value   default value
	 *
	 * @return string
	 */
	public function getString( $key, $default_value = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkString( 2, $default_value, TRUE );

		return Charcoal_ArrayTrait::getString( $this->_values, $key, $default_value );
	}

	/**
	 *  Get element value as array
	 *
	 * @param string $key            Key string to get
	 * @param array $default_value   default value
	 *
	 * @return array
	 */
	public function getArray( $key, $default_value = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkVector( 2, $default_value, TRUE );

		return Charcoal_ArrayTrait::getArray( $this->_values, $key, $default_value );
	}

	/**
	 *  Get element value as associative array
	 *
	 * @param string $key            Key string to get
	 * @param array $default_value   default value
	 *
	 * @return array
	 */
	public function getHashMap( $key, $default_value = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkHashMap( 2, $default_value, TRUE );

		return Charcoal_ArrayTrait::getHashMap( $this->_values, $key, $default_value );
	}

	/**
	 *  Get element value as boolean
	 *
	 * @param string $key           Key string to get
	 * @param bool $default_value   default value
	 *
	 * @return bool
	 */
	public function getBoolean( $key, $default_value = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkBoolean( 2, $default_value, TRUE );

		return Charcoal_ArrayTrait::getBoolean( $this->_values, $key, $default_value );
	}

	/**
	 *  Get element value as integer
	 *
	 * @param string $key          Key string to get
	 * @param int $default_value   default value
	 *
	 * @return int
	 */
	public function getInteger( $key, $default_value = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkInteger( 2, $default_value, TRUE );

		return Charcoal_ArrayTrait::getInteger( $this->_values, $key, $default_value );
	}

	/**
	 *  Get element value as float
	 *
	 * @param string $key            Key string to get
	 * @param float $default_value   default value
	 *
	 * @return float
	 */
	public function getFloat( $key, $default_value = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkFloat( 2, $default_value, TRUE );

		return Charcoal_ArrayTrait::getFloat( $this->_values, $key, $default_value );
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

}

