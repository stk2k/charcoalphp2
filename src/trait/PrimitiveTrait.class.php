<?php
/**
* primitive function trait
*
* PHP version 5
*
* @package    traits
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_PrimitiveTrait
{
	/**
	 * check if the value type seems to be boolean
	 *
	 * @param mixed $value            value to check
	 * @param bool $strict            if TRUE, no cast or conversion will be executed in evaluation
	 * 
	 * @return bool                   TRUE if the value type is boolean, FALSE otherwise 
	 */
	public static function is_string( $value, $strict = TRUE )
	{
		if ( $strict )   return is_string( $value );

		if ( $value instanceof Charcoal_String ){
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * check if the value type seems to be boolean
	 *
	 * @param mixed $value            value to check
	 * @param bool $strict            if TRUE, no cast or conversion will be executed in evaluation
	 * 
	 * @return bool                   TRUE if the value type is boolean, FALSE otherwise 
	 */
	public static function is_bool( $value, $strict = TRUE )
	{
		if ( $strict )   return $value === TRUE || $value === FALSE;

		if ( $value instanceof Charcoal_Boolean ){
			return TRUE;
		}

		if ( is_string($value) ){
			$value = strtolower($value);

			return in_array( $value, array( 'true', 'on', 'yes' ) );
		}

		return FALSE;
	}

	/**
	 * check if the value type seems to be integer
	 *
	 * @param mixed $value            value to check
	 * @param bool $strict            if TRUE, no cast or conversion will be executed in evaluation
	 * 
	 * @return bool                   TRUE if the value type is integer, FALSE otherwise 
	 */
	public static function is_int( $value, $strict = TRUE )
	{
		if ( $strict )   return is_int($value);

		if ( is_int($value) )  return TRUE;
		if ( ctype_digit($value) )  return TRUE;
		if ( $value == intval($value) )  return TRUE;

		return preg_match('/^-?[0-9]+$/', (string)$value) ? TRUE : FALSE;
	}

	/**
	 * check if the value type seems to be float
	 *
	 * @param mixed $value            value to check
	 * @param bool $strict            if TRUE, no cast or conversion will be executed in evaluation
	 * 
	 * @return bool                   TRUE if the value type is float, FALSE otherwise 
	 */
	public static function is_float( $value, $strict = TRUE )
	{
		if ( $strict )   return is_float($value);

		if ( is_float($value) )  return TRUE;
		if ( is_numeric($value) )  return TRUE;

		return FALSE;
	}

	/**
	 * check if the value type seems to be array
	 *
	 * @param mixed $value            value to check
	 * @param bool $strict            if TRUE, no cast or conversion will be executed in evaluation
	 * 
	 * @return bool                   TRUE if the value type is array, FALSE otherwise 
	 */
	public static function is_array( $value, $strict = TRUE )
	{
		if ( $strict )   return is_array($value);

		if ( is_array($value) )  return TRUE;
		if ( !is_string($value) )  return FALSE;

		$value = trim($value);

		if ( strlen($value) === 0 )  return FALSE;

		return TRUE;
	}

	/**
	 * cast a value seems to be boolean into bool value
	 *
	 * @param mixed $value            value to cast
	 * 
	 * @return bool                   casted value
	 */
	public static function boolVal( $value )
	{
		if ( is_bool($value) )   return $value;

		if ( is_string($value) ){
			$value = strtolower($value);

			if ( in_array( $value, array( '1', 'true', 'on', 'yes' ) ) )	return TRUE;
		
			return FALSE;
		}

		_throw( new Charcoal_BooleanFormatException( $value ) );
	}

	/**
	 * cast a value seems to be integer into integer value
	 *
	 * @param mixed $value            value to check
	 * 
	 * @return int                    casted value
	 */
	public static function intVal( $value )
	{
		if ( is_int($value) )  return $value;

		if ( ctype_digit($value) )  return intval($value);
		if ( $value == intval($value) )  return intval($value);

		if ( preg_match('/^-?[0-9]+$/', (string)$value) )  return intval($value);

		_throw( new Charcoal_IntegerFormatException( $value ) );
	}

	/**
	 * cast a value seems to be float into float value
	 *
	 * @param mixed $value            value to check
	 * 
	 * @return float                    casted value
	 */
	public static function floatVal( $value )
	{
		if ( is_float($value) )  return $value;

		if ( is_numeric($value) )  return floatVal( $value );

		_throw( new Charcoal_FloatFormatException( $value ) );
	}

	/**
	 * check if the value type seems to be array
	 *
	 * @param mixed $value            value to check
	 * 
	 * @return array                    casted value
	 */
	public static function arrayVal( $value )
	{
		if ( is_array($value) )  return $value;

		if ( is_string($value) ){
			$value = trim($value);

			if ( strlen($value) > 0 ){
				$values = explode( ',', $value );
				$values = array_map( 'trim', $values );
				return $values;
			}

			return array('');
		}

		_throw( new Charcoal_ArrayFormatException( $value ) );
	}

	/**
	 * check if the value type seems to be associative array
	 *
	 * @param mixed $value            value to check
	 * 
	 * @return array                    casted value
	 */
	public static function hashmapVal( $value )
	{
		$array_value = self::arrayVal( $value );

		$ret = array();
		foreach( $array_value as $array_element ){
			$pos = strpos( $array_element, ':' );
			if ( $pos === FALSE ){
				_throw( new Charcoal_HashMapFormatException( $value ) );
			}
			$key = substr( $array_element, 0, $pos );
			$value = substr( $array_element, $pos + 1 );
			$ret[$key] = $value;
		}

		return $ret;
	}

	/**
	 * check specified value is TRUE or b(TRUE)
	 *
	 * @param mixed $value            value to cast
	 * 
	 * @return bool                   casted value
	 */
	public static function isTrue( $value )
	{
		if ( is_bool($value) )   return $value;

		if ( $value instanceof Charcoal_Boolean ){
			return $value->isTrue();
		}

		_throw( new Charcoal_BooleanFormatException( $value ) );
	}

	/**
	 * check specified value is FALSE or b(FALSE)
	 *
	 * @param mixed $value            value to cast
	 * 
	 * @return bool                   casted value
	 */
	public static function isFalse( $value )
	{
		if ( is_bool($value) )   return $value ? FALSE : TRUE;

		if ( $value instanceof Charcoal_Boolean ){
			return $value->isFalse();
		}

		_throw( new Charcoal_BooleanFormatException( $value ) );
	}


}

