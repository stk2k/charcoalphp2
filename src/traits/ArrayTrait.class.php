<?php
/**
* array function trait
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
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
	 * @return Charcoal_String 
	 */
	public static function getString( $data, $key, $default_value = NULL )
	{
		$value = isset( $data[$key] ) ? $data[$key] : NULL;

		// return default value if the element is null
		if ( NULL === $value ){
			return us($default_value);
		}

		// throws exception if the element's type is not match for required type
		if ( !is_string($value) && !($value instanceof Charcoal_String) ){
			_throw( new Charcoal_StringFormatException( $value ) );
		}

		return us($value);
	}

	/**
	 * Get as array value
	 *
	 * @param array $data             array data
	 * @param string $key             key string for hash map
	 * @param array $default_value   default value
	 * 
	 * @return Charcoal_Vector 
	 */
	public static function getArray( $data, $key, $default_value = NULL )
	{
		$value = isset( $data[$key] ) ? $data[$key] : NULL;

		// return default value if the element is null
		if ( NULL === $value ){
			return uv($default_value);
		}

		// cast to array
		if ( !is_array( $value ) ){
			$value = Charcoal_PrimitiveTrait::arrayVal( $value );
		}

		// throws exception if the element's type is not match for required type
		if ( !is_array( $value ) && !($value instanceof Charcoal_Vector) ){
			_throw( new Charcoal_ArrayFormatException( $value ) );
		}

		return uv($value);
	}

	/**
	 * Get as associative array value
	 *
	 * @param array $data             array data
	 * @param string $key             key string for hash map
	 * @param array $default_value   default value
	 * 
	 * @return Charcoal_Vector 
	 */
	public static function getHashMap( $data, $key, $default_value = NULL )
	{
		$value = isset( $data[$key] ) ? $data[$key] : NULL;

		// return default value if the element is null
		if ( NULL === $value ){
			return um($default_value);
		}

		// cast to array
		if ( !is_array( $value ) ){
			$value = Charcoal_PrimitiveTrait::hashmapVal( $value );
		}

		// throws exception if the element's type is not match for required type
		if ( !is_array( $value ) && !($value instanceof Charcoal_HashMap) ){
			_throw( new Charcoal_HashMapFormatException( $value ) );
		}

		return um($value);
	}

	/**
	 * Get as integer value
	 *
	 * @param array $data             array data
	 * @param string $key             key string for hash map
	 * @param array $default_value   default value
	 * 
	 * @return Charcoal_Integer
	 */
	public static function getInteger( $data, $key, $default_value = NULL )
	{
		$value = isset( $data[$key] ) ? $data[$key] : NULL;

		// return default value if the element is null
		if ( NULL === $value ){
			return ui($default_value);
		}

		// cast to integer
		if ( !is_int( $value ) ){
			$value = Charcoal_PrimitiveTrait::intVal( $value );
		}

		// throws exception if the element's type is not match for required type
		if ( !is_int( $value ) && !($value instanceof Charcoal_Integer) ){
			_throw( new Charcoal_IntegerFormatException( $key ) );
		}

		return ui($value);
	}

	/**
	 * Get as float value
	 *
	 * @param array $data             array data
	 * @param string $key             key string for hash map
	 * @param float $default_value   default value
	 * 
	 * @return Charcoal_Float
	 */
	public static function getFloat( $data, $key, $default_value = NULL )
	{
		$value = isset( $data[$key] ) ? $data[$key] : NULL;

		// return default value if the element is null
		if ( NULL === $value ){
			return uf($default_value);
		}

		// cast to float
		if ( !is_float( $value ) ){
			$value = Charcoal_PrimitiveTrait::floatVal( $value );
		}

		// throws exception if the element's type is not match for required type
		if ( !is_float( $value ) && !($value instanceof Charcoal_Float) ){
			_throw( new Charcoal_FloatFormatException( $key ) );
		}

		return uf($value);
	}

	/**
	 * Get as boolean value
	 *
	 * @param array $data             array data
	 * @param string $key             key string for hash map
	 * @param bool $default_value   default value
	 * 
	 * @return Charcoal_Boolean
	 */
	public static function getBoolean( $data, $key, $default_value = NULL )
	{
		$value = isset( $data[$key] ) ? $data[$key] : NULL;

		// return default value if the element is null
		if ( NULL === $value ){
			return ub($default_value);
		}

		// cast to float
		if ( !is_bool( $value ) ){
			$value = Charcoal_PrimitiveTrait::boolVal( $value );
		}

		// throws exception if the element's type is not match for required type
		if ( !is_bool( $value ) && !($value instanceof Charcoal_Boolean) ){
			_throw( new Charcoal_BooleanFormatException( $key ) );
		}

		return ub($value);
	}

}

