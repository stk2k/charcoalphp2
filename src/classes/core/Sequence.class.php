<?php
/**
* シークエンスクラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Sequence extends Charcoal_Object implements Charcoal_IProperties, Iterator, ArrayAccess, Countable
{
	private $_values;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $values = array() )
	{
		parent::__construct();

		$this->_values = array();
	}

	/*
	 *	エントリの総数を取得
	 */
	public function count()
	{
		return count( $this->_values );
	}

	/*
	 *	Iteratorインタフェース:rewidの実装
	 */
	public function rewind() {
		reset($this->_values);
	}

	/*
	 *	Iteratorインタフェース:currentの実装
	 */
	public function current() {
		$var = current($this->_values);
		return $var;
	}

	/*
	 *	Iteratorインタフェース:keyの実装
	 */
	public function key() {
		$var = key($this->_values);
		return $var;
	}

	/*
	 *	Iteratorインタフェース:nextの実装
	 */
	public function next() {
		$var = next($this->_values);
		return $var;
	}

	/*
	 *	Iteratorインタフェース:validの実装
	 */
	public function valid() {
		$var = $this->current() !== false;
		return $var;
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
	 *	Check if the collection is empty
	 *	
	 *	@return bool        TRUE if this collection has no elements, FALSE otherwise
	 */
	public function setArray( $array )
	{
		Charcoal_ParamTrait::checkArray( 1, $array );

		$this->_values = array_merge( $this->_values, $array );
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

	/*
	 * プロパティの取得
	 */
	public function __get( $key )
	{
		return $this->offsetGet( $key );
	}

	/*
	 * プロパティの設定
	 */
	public function __set( $key, $value )
	{
		$this->offsetSet( $key, $value );
	}

	/*
	 *	キー一覧を取得
	 */
	public function keys()
	{
		return new Charcoal_Vector( array_keys( $this->_values ) );
	}

	/*
	 *	要素値を更新
	 */
	public function set( $key, $value )
	{
		$this->offsetSet( $key, $value );
	}

	/**
	 *  get list of key
	 *
	 * @param string $key            Key string to get
	 *
	 * @return array      
	 */
	public function getKeys() {
		return v(array_keys($this->_values));
	}

	/**
	 *  check if this object has specified key
	 *
	 * @param string $key            Key string to get
	 *
	 * @return bool      TRUE if this object has specified key, otherwise FALSE
	 */
	public function keyExists( $key )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );

		$key = us($key);
		return array_key_exists($key,$this->_values);
	}

	/**
	 *  Get element value
	 *
	 * @param string $key            Key string to get
	 * @param array $default_value   default value
	 *
	 * @return mixed
	 */
	public function get( $key, $defaultValue =NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );

		return $this->offsetGet( $key );
	}

	/*
	 *	ArrayAccessインタフェース:offsetGetの実装
	 */
	public function offsetGet( $offset )
	{
//		Charcoal_ParamTrait::checkString( 1, $offset );

		$key = us( $key );

		$value = isset($this->_values[ $key ]) ? $this->_values[ $key ] : NULL;

		return $value;
	}

	/*
	 *	ArrayAccessインタフェース:offsetSetの実装
	 */
	public function offsetSet( $key, $value )
	{
//		Charcoal_ParamTrait::checkString( 1, $offset );

		$key = us( $key );

		$this->_values[ $key ] = $value;
	}

	/*
	 *	ArrayAccessインタフェース:offsetExistsの実装
	 */
	public function offsetExists( $key )
	{
//		Charcoal_ParamTrait::checkString( 1, $offset );

		$key = us( $key );

		return isset($this->_values[$key]);
	}

	/*
	 *	ArrayAccessインタフェース:offsetUnsetの実装
	 */
	public function offsetUnset($offset)
	{
//		Charcoal_ParamTrait::checkString( 1, $offset );

		$key = us( $key );

		unset($this->_values[$key]);
	}

	/*
	 *  Get element value as string
	 *
	 * @param string $key   Key string to get
	 * @param string $default_value   default value
	 *
	 * @return string
	 */
	public function getString( $key, $default_value = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkString( 2, $default_value, TRUE );

		$value = $this->offsetGet( $key );

		// return default value if the value is not string type
		if ( NULL === $value || !is_string($value) ){
			return us( $default_value );
		}

		return $value;
	}

	/*
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
//		Charcoal_ParamTrait::checkString( 2, $default_value, TRUE );

		$value = $this->offsetGet( $key );

		// return default value if the value is not array type
		if ( NULL === $value || !is_array($value) ){
			return uv( $default_value );
		}

		return $value;
	}

	/*
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

		$value = $this->offsetGet( $key );

		if ( is_string($value) ){
			$value = strtolower($value);
			switch($value){
			case 'true':
			case 'on':
			case 'yes':
				$value = TRUE;
				break;
			default:
				$value = FALSE;
				break;
			}
		}

		// return default value if the value is not boolean type
		if ( NULL === $value || !is_bool($value) ){
			return ub( $default_value );
		}

		return $value;
	}

	/*
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

		$value = $this->offsetGet( $key );

		// return default value if the value is not int type
		if ( NULL === $value || !is_numeric($value) ){
			return $default_value;
		}

		return $value;
	}

	/*
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

		$value = $this->offsetGet( $key );

		// return default value if the value is not int type
		if ( NULL === $value || !is_numeric($value) ){
			return uf( $default_value );
		}

		return $value;
	}

	/*
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

}


