<?php
/**
*
* Property set container(Read-Only)
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_Properties extends Charcoal_HashMap implements Charcoal_IProperties
{
	/**
	 * Retrieve default value
	 *
	 * @return Charcoal_Properties        default value
	 */
	public static function defaultValue()
	{
		return new self();
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
//		Charcoal_ParamTrait::validateString( 1, $key );

		$key = us($key);
		return parent::keyExists( $key );
	}

	/**
	 *	ArrayAccess interface : offsetGet() implementation
	 */
	public function offsetGet( $key )
	{
//		Charcoal_ParamTrait::validateString( 1, $key );

		$key = us( $key );
		return parent::offsetGet( $key );
	}

	/**
	 *	ArrayAccess interface : offsetSet() implementation
	 */
	public function offsetSet( $key, $value )
	{
//		Charcoal_ParamTrait::validateString( 1, $key );

		$key = us( $key );
		parent::offsetSet( $key, $value );
	}

	/**
	 *	ArrayAccess interface : offsetExists() implementation
	 */
	public function offsetExists( $key )
	{
//		Charcoal_ParamTrait::validateString( 1, $key );

		$key = us( $key );
		return parent::offsetExists( $key );
	}

	/**
	 *	ArrayAccess interface : offsetUnset() implementation
	 */
	public function offsetUnset( $key )
	{
//		Charcoal_ParamTrait::validateString( 1, $key );

		$key = us( $key );
		parent::offsetUnset( $key );
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
//		Charcoal_ParamTrait::validateString( 1, $key );

		$key = us($key);
		return parent::get( $key );
	}

	/**
	 *  Get element value as string
	 *
	 * @param string $key             Key string to get
	 * @param string $default_value   default value
	 *
	 * @return string
	 */
	public function getString( $key, $default_value = NULL, $encoding = NULL )
	{
//		Charcoal_ParamTrait::validateString( 1, $key );

		$key = us($key);
		return parent::getString( $key, $default_value, $encoding );
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
//		Charcoal_ParamTrait::validateString( 1, $key );

		$key = us($key);
		return parent::getArray( $key, $default_value );
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
//		Charcoal_ParamTrait::validateString( 1, $key );

		$key = us($key);
		return parent::getBoolean( $key, $default_value );
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
//		Charcoal_ParamTrait::validateString( 1, $key );

		$key = us($key);
		return parent::getInteger( $key, $default_value );
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
//		Charcoal_ParamTrait::validateString( 1, $key );

		$key = us($key);
		return parent::getFloat( $key, $default_value );
	}

}

