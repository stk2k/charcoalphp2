<?php
/**
* データ転送クラス
*
* PHP version 5
*
* @package	core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_DTO extends Charcoal_Object implements Iterator, ArrayAccess
{
	private $_values;

	/**
	 *	constructor
	 */
	public function __construct( $values = array() )
	{
		parent::__construct();

		$this->_values = array();

		$vars = get_object_vars($this);
		unset($vars['_values']);

		$vars = array_merge( $vars, $values );

		$this->setArray( $vars );
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
		$key = key($this->_values);
		if ( property_exists($this,$key) ){
			$var = $this->$key;
		}
		else{
			$var = current($this->_values);
		}
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
	 *	Get element value
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
		if ( property_exists($this,$offset) ){
			return $this->$offset;
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
		return isset($this->_values[$offset]);
	}

	/**
	 *	ArrayAccess interface : offsetUnset() implementation
	 */
	public function offsetUnset($offset)
	{
		unset($this->_values[$offset]);
		if ( property_exists($this,$offset) ){
			$this->$offset = NULL;
		}
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
	public function setArray( $array )
	{
		foreach( $array as $key => $value ){
			$this->offsetSet( $key, $value );
		}
	}

	/**
	 *	Set all hashmap elements
	 */
	public function setHashMap( $map )
	{
		$map = um($map);

		foreach( $map as $key => $value ){
			$this->offsetSet( $key, $value );
		}
	}

	/**
	 *	Merge with array
	 */
	public function mergeArray( $array, $overwrite = TRUE )
	{
		foreach( $array as $key => $value ){
			if ( !$this->keyExists($key) || $overwrite ){
				$this->offsetSet( $key, $value );
			}
		}
	}

	/**
	 *	Merge with hashmap
	 */
	public function mergeHashMap( $obj, $overwrite = TRUE )
	{
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

