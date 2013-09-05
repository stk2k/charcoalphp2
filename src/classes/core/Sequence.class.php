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

	/*
	 *	全要素値を取得
	 */
	public function getAll()
	{
		return $this->_values;
	}

	/*
	 *	配列の全要素を追加
	 */
	public function setArray( array $array )
	{
		$this->_values = array_merge( $this->_values, $array );
	}

	/*
	 *	空か
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
	public function set( Charcoal_String $key, $value )
	{
		$this->offsetSet( $key, $value );
	}

	/*
	 *	キー一覧を取得
	 */
	public function getKeys() {
		return v(array_keys($this->_values));
	}

	/*
	 *  キーがあるか
	 */
	public function keyExists( Charcoal_String $key )
	{
		$key = us($key);
		return array_key_exists($key,$this->_values);
	}

	/*
	 *	要素値を取得
	 */
	public function get( Charcoal_String $key, $defaultValue =NULL )
	{
		return $this->offsetGet( $key );
	}

	/*
	 *	ArrayAccessインタフェース:offsetGetの実装
	 */
	public function offsetGet($offset)
	{
		if ( is_object($offset) ){
			$offset = $offset->__toString();
		}
		$value = isset($this->_values[ $offset ]) ? $this->_values[ $offset ] : NULL;

		return $value;
	}

	/*
	 *	ArrayAccessインタフェース:offsetSetの実装
	 */
	public function offsetSet($offset, $value)
	{
		if ( is_object($offset) ){
			$offset = $offset->__toString();
		}
		$this->_values[ $offset ] = $value;
	}

	/*
	 *	ArrayAccessインタフェース:offsetExistsの実装
	 */
	public function offsetExists($offset)
	{
		return isset($this->_values[$offset]);
	}

	/*
	 *	ArrayAccessインタフェース:offsetUnsetの実装
	 */
	public function offsetUnset($offset)
	{
		unset($this->_values[$offset]);
	}

	/*
	 *  Get element value as string
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_String
	 */
	public function getString( Charcoal_String $key, Charcoal_String $default_value = NULL )
	{
		$value = $this->offsetGet( $key );

		// 文字列として不正ならデフォルト値を返す
		if ( NULL === $value || !is_string($value) ){
			return $default_value;
		}

		return s($value);
	}

	/*
	 *  Get element value as array
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_Vector
	 */
	public function getArray( Charcoal_String $key, Charcoal_Vector $default_value = NULL )
	{
		$value = $this->offsetGet( $key );

		// 配列値として不正ならデフォルト値を返す
		if ( NULL === $value || !is_array($value) ){
			return $default_value;
		}

		// 配列を返却
		return  v($value);
	}

	/*
	 *  Get element value as boolean
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_Boolean
	 */
	public function getBoolean( Charcoal_String $key, Charcoal_Boolean $default_value = NULL )
	{
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

		// ブール値として不正ならデフォルト値を返す
		if ( NULL === $value || !is_bool($value) ){
			return $default_value;
		}

		// ブール型にして返却
		return b($value);
	}

	/*
	 *  Get element value as integer
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_Integer
	 */
	public function getInteger( Charcoal_String $key, Charcoal_Integer $default_value = NULL )
	{
		$value = $this->offsetGet( $key );

		// 整数値として不正ならデフォルト値を返す
		if ( NULL === $value || !is_numeric($value) ){
			return $default_value;
		}

		// 整数型にして返却
		return i($value);
	}

	/*
	 *  Get element value as float
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_Float
	 */
	public function getFloat( Charcoal_String $key, Charcoal_Float $default_value = NULL )
	{
		$value = $this->offsetGet( $key );

		// 浮動小数点数として不正ならデフォルト値を返す
		if ( NULL === $value || !is_numeric($value) ){
			return $default_value;
		}

		// 浮動小数点数型にして返却
		return f($value);
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


