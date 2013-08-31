<?php
/**
* シークエンスホルダクラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_SequenceHolder extends Charcoal_Object implements Charcoal_ISequence, ArrayAccess
{
	private $_local;
	private $_global;

	/*
	 *	コンストラクタ
	 */
	public function __construct( Charcoal_Sequence $global, Charcoal_Sequence $local )
	{
		parent::__construct();

		$this->_global = $global;
		$this->_local  = $local;
	}

	/*
	 *  Get all values
	 *
	 * @return array
	 */
	public function getAll()
	{
		$global_values = $this->_global->getAll();
		$local_values  = $this->_local->getAll();

		return array_merge( $global_values, $local_values );
	}

	/*
	 *  Return if property has specified key
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return bool   TRUE if the key exists, otherwise FALSE
	 */
	public function keyExists( Charcoal_String $key )
	{
		if ( $this->_global->keyExists($key) ){
			return true;
		}
		if ( $this->_local->keyExists($key) ){
			return true;
		}

		return false;
	}

	/**
	 *  Get element value as string
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_String
	 */
	public function getString( Charcoal_String $key, Charcoal_String $default_value = NULL )
	{
		if ( $this->_global->keyExists($key) ){
			return $this->_global->getString( $key );
		}
		if ( $this->_local->keyExists($key) ){
			return $this->_local->getString( $key );
		}

		return $default_value ? $default_value : s('');
	}

	/**
	 *  Get element value as array
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_Vector
	 */
	public function getArray( Charcoal_String $key, Charcoal_Vector $default_value = NULL )
	{
		if ( $this->_global->keyExists($key) ){
			return $this->_global->getArray( $key );
		}
		if ( $this->_local->keyExists($key) ){
			return $this->_local->getArray( $key );
		}

		return $default_value;
	}

	/**
	 *  Get element value as boolean
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_Vector
	 */
	public function getBoolean( Charcoal_String $key, Charcoal_Boolean $default_value = NULL )
	{
		if ( $this->_global->keyExists($key) ){
			return $this->_global->getBoolean( $key );
		}
		if ( $this->_local->keyExists($key) ){
			return $this->_local->getBoolean( $key );
		}

		return $default_value;
	}

	/**
	 *  Get element value as integer
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_Vector
	 */
	public function getInteger( Charcoal_String $key, Charcoal_Integer $default_value = NULL )
	{
		if ( $this->_global->keyExists($key) ){
			return $this->_global->getInteger( $key );
		}
		if ( $this->_local->keyExists($key) ){
			return $this->_local->getInteger( $key );
		}

		return $default_value;
	}

	/**
	 *  Get element value as float
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_Vector
	 */
	public function getFloat( Charcoal_String $key, Charcoal_Float $default_value = NULL )
	{
		if ( $this->_global->keyExists($key) ){
			return $this->_global->getFloat( $key );
		}
		if ( $this->_local->keyExists($key) ){
			return $this->_local->getFloat( $key );
		}

		return $default_value;
	}

	/*
	 *      ArrayAccessインタフェース:offsetGetの実装
	 */
	public function offsetGet($offset)
	{
		if ( $offset == 'global' ){
			return $this->_global;
		}
		if ( $offset == 'local' ){
			return $this->_local;
		}

		return $this->get( s($offset) );
	}

	/*
	 *      ArrayAccessインタフェース:offsetSetの実装
	 */
	public function offsetSet($offset, $value)
	{
		if ( $offset instanceof Charcoal_String ){
			$this->set($offset,$value);
		}
		else if ( is_string($offset) ){
			$this->set(s($offset),$value);
		}
	}

	/*
	 *      ArrayAccessインタフェース:offsetExistsの実装
	 */
	public function offsetExists($offset)
	{
		if ( $offset instanceof Charcoal_Object ){
			$offset =us( $offset );
		}
		return isset($this->_global[$offset]) || isset($this->_local[$offset]);
	}

	/*
	 *      ArrayAccessインタフェース:offsetUnsetの実装
	 */
	public function offsetUnset($offset)
	{
		if ( $offset instanceof Charcoal_Object ){
			$offset = us( $offset );
		}
		unset( $this->_global[$offset] );
		unset( $this->_local[$offset] );
	}

	/*
	 *	キー一覧を取得
	 */
	public function getKeys() 
	{
		$global_keys = $this->_global->getKeys();
		$local_keys  = $this->_local->getKeys();

		$keys = array_merge( uv($global_keys), uv($local_keys) );

		return $keys;
	}

	/*
	 *    パラメータを取得
	 */
	public function get( Charcoal_String $key )
	{
		$key = us($key);

		if ( isset($this->_local[$key]) ){
			return $this->_local[$key];
		}
		if ( isset($this->_global[$key]) ){
			return $this->_global[$key];
		}

		return NULL;
	}

	/*
	 *    globalパラメータを取得
	 */
	public function getGlobal( Charcoal_String $key )
	{
		$key = us($key);

		if ( isset($this->_global[$key]) ){
			return $this->_global[$key];
		}

		return NULL;
	}

	/*
	 *    localパラメータを取得
	 */
	public function getLocal( Charcoal_String $key )
	{
		$key = us($key);

		if ( isset($this->_local[$key]) ){
			return $this->_local[$key];
		}

		return NULL;
	}

	/*
	 *    パラメータを設定
	 */
	public function set( Charcoal_String $key, $value )
	{
		$key = us($key);

		if ( strstr($key,'@global') === 0 ){
			$this->_global[$key] = $value;
		}
		else{
			$this->_local[$key] = $value;
		}
	}

	/*
	 *    globalパラメータを設定
	 */
	public function setGlobal( Charcoal_String $key, $value )
	{
		$this->_global->set( $key, $value );
	}

	/*
	 *    localパラメータを設定
	 */
	public function setLocal( Charcoal_String $key, $value )
	{
		$this->_local->set( $key, $value );
	}
}


