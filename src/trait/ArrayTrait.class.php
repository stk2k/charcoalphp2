<?php
/**
* array function trait
*
* PHP version 5
*
* @package    traits
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ArrayTrait
{
	/**
	 * Get as string value
	 *
	 * @param array $data             array data
	 * @param string $key             key string for hash map
	 * @param string $default_value   default value
	 * 
	 * @return Charcoal_String|NULL
	 */
	public static function getString( $data, $key, $default_value = NULL )
	{
		$value = isset( $data[$key] ) ? $data[$key] : NULL;

		// return default value if the element is null
		if ( NULL === $value ){
			return $default_value;
		}

		// throws exception if the element's type is not match for required type
		if ( !is_string($value) && !($value instanceof Charcoal_String) ){
			_throw( new Charcoal_StringFormatException( $value ) );
		}

		return s($value);
	}

	/**
	 * Get as array value
	 *
	 * @param array $data             array data
	 * @param string $key             key string for hash map
	 * @param array $default_value   default value
	 * 
	 * @return Charcoal_Vector|NULL
	 */
	public static function getArray( $data, $key, $default_value = NULL )
	{
		$value = isset( $data[$key] ) ? $data[$key] : NULL;

		// return default value if the element is null
		if ( NULL === $value ){
			return $default_value;
		}

		// cast to array value
		if ( is_scalar( $value ) ){
			$value = Charcoal_ScalarTrait::arrayVal( $value );
		}

		// throws exception if the element's type is not match for required type
		if ( !is_array( $value ) && !($value instanceof Charcoal_Vector) ){
			_throw( new Charcoal_ArrayFormatException( $value ) );
		}

		return v($value);
	}

	/**
	 * Get as associative array value
	 *
	 * @param array $data             array data
	 * @param string $key             key string for hash map
	 * @param array $default_value   default value
	 * 
	 * @return Charcoal_Vector|NULL
	 */
	public static function getHashMap( $data, $key, $default_value = NULL )
	{
		$value = isset( $data[$key] ) ? $data[$key] : NULL;

		// return default value if the element is null
		if ( NULL === $value ){
			return $default_value;
		}

		// cast to hash map value
		if ( is_scalar( $value ) ){
			$value = Charcoal_ScalarTrait::hashmapVal( $value );
		}

		// throws exception if the element's type is not match for required type
		if ( !is_array( $value ) && !($value instanceof Charcoal_HashMap) ){
			_throw( new Charcoal_HashMapFormatException( $value ) );
		}

		return m($value);
	}

	/**
	 * Get as integer value
	 *
	 * @param array $data             array data
	 * @param string $key             key string for hash map
	 * @param array $default_value   default value
	 * 
	 * @return Charcoal_Integer|NULL
	 */
	public static function getInteger( $data, $key, $default_value = NULL )
	{
		$value = isset( $data[$key] ) ? $data[$key] : NULL;

		// return default value if the element is null
		if ( NULL === $value ){
			return $default_value;
		}

		// cast to integer value
		if ( is_scalar( $value ) ){
			$value = Charcoal_ScalarTrait::intVal( $value );
		}

		// throws exception if the element's type is not match for required type
		if ( !is_int( $value ) && !($value instanceof Charcoal_Integer) ){
			_throw( new Charcoal_IntegerFormatException( $key ) );
		}

		return i($value);
	}

	/**
	 * Get as float value
	 *
	 * @param array $data             array data
	 * @param string $key             key string for hash map
	 * @param float $default_value   default value
	 * 
	 * @return Charcoal_Float|NULL
	 */
	public static function getFloat( $data, $key, $default_value = NULL )
	{
		$value = isset( $data[$key] ) ? $data[$key] : NULL;

		// return default value if the element is null
		if ( NULL === $value ){
			return $default_value;
		}

		// cast to float value
		if ( is_scalar( $value ) ){
			$value = Charcoal_ScalarTrait::floatVal( $value );
		}

		// throws exception if the element's type is not match for required type
		if ( !is_float( $value ) && !($value instanceof Charcoal_Float) ){
			_throw( new Charcoal_FloatFormatException( $key ) );
		}

		return f($value);
	}

	/**
	 * Get as boolean value
	 *
	 * @param array $data             array data
	 * @param string $key             key string for hash map
	 * @param bool $default_value   default value
	 * 
	 * @return Charcoal_Boolean|NULL
	 */
	public static function getBoolean( $data, $key, $default_value = NULL )
	{
		$value = isset( $data[$key] ) ? $data[$key] : NULL;

		// return default value if the element is null
		if ( NULL === $value ){
			return $default_value;
		}

		// cast to boolean value
		if ( is_scalar( $value ) ){
			$value = Charcoal_ScalarTrait::boolVal( $value );
		}

		// throws exception if the element's type is not match for required type
		if ( !is_bool( $value ) && !($value instanceof Charcoal_Boolean) ){
			_throw( new Charcoal_BooleanFormatException( $key ) );
		}

		return b($value);
	}

}

