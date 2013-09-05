<?php
/**
* parameter check trait
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ParamTrait
{
	/**
	 *	check if the value is a boolean value
	 */
	public static function isBool( $value )
	{
		if ( $value === TRUE || $value === FALSE )	return TRUE;
		return FALSE;
	}

	/**
	 *	check a parameter
	 */
	public static function check( $key, $types, $actual )
	{
		if ( $actual === NULL )	return;

		static $check_funcs;

		if ( !$check_funcs ){
			$check_funcs = array(
					'string' => 'is_string',
					'array' => 'is_array',
					'int' => 'is_numeric',
					'integer' => 'is_numeric',
					'float' => 'is_numeric',
					'bool' => 'Charcoal_ParamTrait::isBool',
					'boolean' => 'Charcoal_ParamTrait::isBool',
					'resource' => 'is_resource',
					'object' => 'is_object',
				);
		}

		$white_list = explode( '/', $types );
		if ( $white_list )
		{
			foreach( $white_list as $type ){
				if ( isset($check_funcs[$type]) ){
					$res = $check_funcs[$type]( $actual );
					if ( $res ) return;
				}
				else if ( $actual instanceof $type ){
					return;
				}
			}
		}
		_throw( new Charcoal_ParameterException( $key, $types, $actual ) );
	}

	/**
	 *	check a parameter if its type is string object or string
	 */
	public static function checkString( $key, $actual )
	{
		self::check( $key, 'string/Chacoal_String', $actual );
	}

	/**
	 *	check a parameter if its type is integer object or int
	 */
	public static function checkInteger( $key, $actual )
	{
		self::check( $key, 'int/Chacoal_Integer', $actual );
	}

	/**
	 *	check a parameter if its type is float object or float
	 */
	public static function checkFloat( $key, $actual )
	{
		self::check( $key, 'float/Chacoal_Float', $actual );
	}

	/**
	 *	check a parameter if its type is array object
	 */
	public static function checkArray( $key, $actual )
	{
		self::check( $key, 'array/Chacoal_Vector', $actual );
	}

	/**
	 *	check a parameter if its type is hash map object
	 */
	public static function checkHashMap( $key, $actual )
	{
		self::check( $key, 'array/Charcoal_HashMap', $actual );
	}

	/**
	 *	check a parameter if its type is a properties object
	 */
	public static function checkProperty( $key, $actual )
	{
		self::check( $key, 'array/Charcoal_Properties', $actual );
	}

	/**
	 *	check a parameter if its type is exception object
	 */
	public static function checkException( $key, $actual )
	{
		self::check( $key, 'Exception', $actual );
	}

	/**
	 *	check a parameter if its type is resource object
	 */
	public static function checkResource( $key, $actual )
	{
		self::check( $key, 'resource', $actual );
	}

	/**
	 *	check a parameter if its type is object
	 */
	public static function checkObject( $key, $actual )
	{
		self::check( $key, 'object', $actual );
	}

	/**
	 *	check a parameter if its type is object
	 */
	public static function checkPrimitive( $key, $actual )
	{
		self::check( $key, 'string/Chacoal_String/int/Chacoal_Integer/float/Chacoal_Float/array/Chacoal_Vector/Charcoal_HashMap/Charcoal_Properties', $actual );
	}

}

