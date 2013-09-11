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

	/**
	 *	Get all values with keys
	 *
	 * @return array
	 */
	public function getAll()
	{
		$global_values = $this->_global->getAll();
		$local_values  = $this->_local->getAll();

		return array_merge( $global_values, $local_values );
	}

	/**
	 *  Return if property has specified key
	 *
	 * @param string $key   Key string to get
	 *
	 * @return bool   TRUE if the key exists, otherwise FALSE
	 */
	public function keyExists( $key )
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
	 * @param string $key             Key string to get
	 * @param string $default_value   default value
	 *
	 * @return string
	 */
	public function getString( $key, $default_value = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkString( 2, $default_value, TRUE );

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
	 * @param string $key            Key string to get
	 * @param array $default_value   default value
	 *
	 * @return array
	 */
	public function getArray( $key, $default_value = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkVector( 2, $default_value, TRUE );

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
	 * @param string $key           Key string to get
	 * @param bool $default_value   default value
	 *
	 * @return bool
	 */
	public function getBoolean( $key, $default_value = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkBoolean( 2, $default_value, TRUE );

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
	 * @param string $key          Key string to get
	 * @param int $default_value   default value
	 *
	 * @return int
	 */
	public function getInteger( $key, $default_value = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkInteger( 2, $default_value, TRUE );

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
	 * @param string $key            Key string to get
	 * @param float $default_value   default value
	 *
	 * @return float
	 */
	public function getFloat( $key, $default_value = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkFloat( 2, $default_value, TRUE );

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

	/**
	 *  Get element value
	 *
	 * @param string $key            Key string to get
	 * @param array $default_value   default value
	 *
	 * @return mixed
	 */
	public function get( $key )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );

		$key = us($key);

		if ( isset($this->_local[$key]) ){
			return $this->_local[$key];
		}
		if ( isset($this->_global[$key]) ){
			return $this->_global[$key];
		}

		return NULL;
	}

	/**
	 *  Get a global parameter
	 *
	 * @param string $key            Key string to get
	 *
	 * @return mixed
	 */
	public function getGlobal( $key )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );

		$key = us($key);

		if ( isset($this->_global[$key]) ){
			return $this->_global[$key];
		}

		return NULL;
	}

	/**
	 *  Get a local parameter
	 *
	 * @param string $key            Key string to get
	 *
	 * @return mixed
	 */
	public function getLocal( $key )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );

		$key = us($key);

		if ( isset($this->_local[$key]) ){
			return $this->_local[$key];
		}

		return NULL;
	}

	/**
	 *  set a parameter
	 *
	 * @param string $key            Key string to get
	 * @param mixed $value           value to set
	 */
	public function set( $key, $value )
	{
		$key = us($key);

		if ( strstr($key,'@global') === 0 ){
			$this->_global[$key] = $value;
		}
		else{
			$this->_local[$key] = $value;
		}
	}

	/**
	 *  set a global parameter
	 *
	 * @param string $key            Key string to get
	 * @param mixed $value           value to set
	 */
	public function setGlobal( $key, $value )
	{
		$this->_global->set( $key, $value );
	}

	/**
	 *  set a local parameter
	 *
	 * @param string $key            Key string to get
	 * @param mixed $value           value to set
	 */
	public function setLocal( $key, $value )
	{
		$this->_local->set( $key, $value );
	}
}


