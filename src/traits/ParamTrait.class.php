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
	// primitive types
	const TYPE_P_STRING      = 'string';
	const TYPE_P_INTEGER     = 'int';
	const TYPE_P_FLOAT       = 'float';
	const TYPE_P_BOOL        = 'bool';
	const TYPE_P_ARRAY       = 'array';
	const TYPE_P_RESOURCE    = 'resource';
	const TYPE_P_OBJECT      = 'object';

	// object types
	const TYPE_O_STRING      = 'Charcoal_String';
	const TYPE_O_INTEGER     = 'Charcoal_Integer';
	const TYPE_O_FLOAT       = 'Charcoal_Float';
	const TYPE_O_BOOLEAN     = 'Charcoal_Boolean';
	const TYPE_O_VECTOR      = 'Charcoal_Vector';
	const TYPE_O_HASHMAP     = 'Charcoal_HashMap';
	const TYPE_O_PROPERTIES  = 'Charcoal_Properties';
	const TYPE_O_OBJECTPATH  = 'Charcoal_ObjectPath';
	const TYPE_O_CONFIG      = 'Charcoal_Config';
	const TYPE_O_EXCEPTION   = 'Exception';
	const TYPE_O_OBJECT      = 'Charcoal_Object';
	const TYPE_O_FILE        = 'Charcoal_File';

	// fraework types
	const TYPE_F_SANDBOX     = 'Charcoal_Sandbox';

	/**
	 * check if the value type seems to be boolean
	 *
	 * @param mixed $value            value to check
	 * 
	 * @return bool                   TRUE if the value type is boolean, FALSE otherwise 
	 */
	public static function is_bool( $value )
	{
		return $value === TRUE || $value === FALSE;
	}

	/**
	 *	test a parameter
	 *	
	 *	@param string $types           type name to check
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return boolean     TRUE if check is OK, otherwise FALSE
	 */
	private static function _testType( $type, $actual, $null_allowed = FALSE )
	{
		if ( $null_allowed )	return TRUE;

/*
		static $check_funcs;

		if ( !$check_funcs ){
			$check_funcs = array(
					'string' => 'is_string',
					'array' => 'is_array',
					'int' => 'is_numeric',
					'integer' => 'is_numeric',
					'float' => 'is_numeric',
					'bool' => 'Charcoal_ParamTrait::_is_bool',
					'boolean' => 'Charcoal_ParamTrait::_is_bool',
					'resource' => 'is_resource',
					'object' => 'is_object',
				);
		}
		if ( isset($check_funcs[$type]) ){
			$func = $check_funcs[$type];
			$res = call_user_func_array( $func, array($actual) );
			if ( $res ){
				return TRUE;
			}
		}
		else if ( $actual instanceof $type ){
			return TRUE;
		}
*/

		switch( $type ){
		case 'string':		return is_string( $actual );
		case 'array':		return is_array( $actual );
		case 'int':			return is_numeric( $actual );
		case 'integer':		return is_numeric( $actual );
		case 'float':		return is_numeric( $actual );
		case 'bool':		return Charcoal_PrimitiveTrait::is_bool( $actual, TRUE );
		case 'boolean':		return Charcoal_PrimitiveTrait::is_bool( $actual, TRUE );
		case 'resource':	return is_resource($actual);
		case 'object':		return is_object($actual);
		default:
			return ( $actual instanceof $type );
		}

		return FALSE;
	}

	/**
	 *	check a parameter
	 *	
	 *	@param int $key                parameter id
	 *	@param string $types           type name to check
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkType( $file, $line, $key, $type, $actual, $null_allowed = FALSE )
	{
		$ret = self::_testType( $type, $actual, $null_allowed);
		if ( $ret ){
			return $ret;
		}
		_throw( new Charcoal_ParameterException( $file, $line, $key, $type, $actual ) );
	}

	/**
	 *	check a parameter
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $types           type list to check
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkTypes( $file, $line, $key, $types, $actual, $null_allowed = FALSE )
	{
		if ( $null_allowed && $actual === NULL )	return 'NULL';

		foreach( $types as $type ){
			$ret = self::_testType( $type, $actual, $null_allowed );
			if ( $ret ){
				return $ret;
			}
		}

		_throw( new Charcoal_ParameterException( $file, $line, $key, $types, $actual ) );
	}

	/**
	 *	check a parameter if its type is string object or string
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkString( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkTypes( $file, $line, $key, array( self::TYPE_P_STRING, self::TYPE_O_STRING ), $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is integer object or int
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkInteger( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkTypes( $file, $line, $key, array( self::TYPE_P_INTEGER, self::TYPE_O_INTEGER ), $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is float object or float
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkFloat( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkTypes( $file, $line, $key, array( self::TYPE_P_FLOAT, self::TYPE_O_FLOAT ), $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is boolean object or boolean
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkBoolean( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkTypes( $file, $line, $key, array( self::TYPE_P_BOOL, self::TYPE_O_BOOLEAN ), $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is PHP string
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkRawString( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkType( $file, $line, $key, self::TYPE_P_STRING, $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is PHP integer
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkRawInteger( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkType( $file, $line, $key, self::TYPE_P_INTEGER, $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is PHP float
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkRawFloat( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkType( $file, $line, $key, self::TYPE_P_FLOAT, $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is PHP bool
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkRawBool( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkType( $file, $line, $key, self::TYPE_P_BOOL, $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is PHP array
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkRawArray( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkType( $file, $line, $key, self::TYPE_P_ARRAY, $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is array object
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkVector( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkTypes( $file, $line, $key, array( self::TYPE_P_ARRAY, self::TYPE_O_VECTOR ), $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is hash map object
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkHashMap( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkTypes( $file, $line, $key, array( self::TYPE_P_ARRAY, self::TYPE_O_HASHMAP ), $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is a properties object
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkProperties( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkTypes( $file, $line, $key, array( self::TYPE_P_ARRAY, self::TYPE_O_PROPERTIES ), $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is object path
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkObjectPath( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkType( $file, $line, $key, self::TYPE_O_OBJECTPATH, $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is config
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkConfig( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkType( $file, $line, $key, self::TYPE_O_CONFIG, $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is exception object
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkException( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkType( $file, $line, $key, self::TYPE_O_EXCEPTION, $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is resource object
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkResource( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkType( $file, $line, $key, self::TYPE_P_RESOURCE, $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is object
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkObject( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkType( $file, $line, $key, self::TYPE_P_OBJECT, $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is charcoal object
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkCharcoalObject( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkType( $file, $line, $key, self::TYPE_O_OBJECT, $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is file object
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkFile( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkType( $file, $line, $key, self::TYPE_O_FILE, $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is object
	 *	
	 *	@param int $key                parameter id
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkPrimitive( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		static $types;
		if ( !$types ){
			$types = array(
						// primitive types
						self::TYPE_P_STRING, self::TYPE_P_INTEGER, self::TYPE_P_FLOAT, self::TYPE_P_BOOL, 
						self::TYPE_P_ARRAY, 
						// object types
						self::TYPE_O_STRING, self::TYPE_O_INTEGER, self::TYPE_O_FLOAT, self::TYPE_O_BOOLEAN, 
						self::TYPE_O_VECTOR, self::TYPE_O_HASHMAP, self::TYPE_O_PROPERTIES,
					);
		}
		return self::checkTypes( $file, $line, $key, $types, $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is string or object
	 *	
	 *	@param int $key                parameter id
	 *	@param string $type            class or interface name for object
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkStringOrObject( $key, $type, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkTypes( $file, $line, $key, array( self::TYPE_P_STRING, self::TYPE_O_STRING, $type ), $actual, $null_allowed );
	}

	/**
	 *	check a parameter if its type is string or object path
	 *	
	 *	@param int $key                parameter id
	 *	@param string $type            class or interface name for object
	 *	@param mixed $actual           data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkStringOrObjectPath( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkTypes( $file, $line, $key, array( self::TYPE_P_STRING, self::TYPE_O_STRING, self::TYPE_O_OBJECTPATH ), $actual, $null_allowed );
	}

	/**
	 *	check a parameter if specified object is sandbox object
	 *	
	 *	@param int $key                  parameter id
	 *	@param mixed $actual             data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkSandbox( $key, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		return self::checkType( $file, $line, $key, self::TYPE_F_SANDBOX, $actual, $null_allowed );
	}

	/**
	 *	check a parameter if the object is of this class or has this class as one of its parents
	 *	
	 *	@param int $key              parameter id
	 *	@param string $class_name    class name of the object should extend from
	 *	@param mixed $actual         data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkIsA( $key, $class_name, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		if ( $null_allowed && $actual === NULL )	return 'NULL';

		if ( $actual instanceof $class_name ){
			return TRUE;
		}
		_throw( new Charcoal_ParameterException( $file, $line, $key, $class_name, $actual ) );
	}

	/**
	 *	check a parameter if specified object implements an interface
	 *	
	 *	@param int $key                  parameter id
	 *	@param string $interface_name   interface name of the object should implement
	 *	@param mixed $actual             data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function checkImplements( $key, $interface_name, $actual, $null_allowed = FALSE )
	{
		list( $file, $line ) = Charcoal_System::caller(1);

		if ( $null_allowed && $actual === NULL )	return 'NULL';

		if ( $actual instanceof $interface_name ){
			return TRUE;
		}
		_throw( new Charcoal_ParameterException( $file, $line, $key, $interface_name, $actual ) );
	}

	/**
	 *	test if specified object is string or string object
	 *	
	 *	@param mixed $actual             data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function isString( $actual, $null_allowed = FALSE )
	{
		if ( self::_testType( self::TYPE_P_STRING, $actual, $null_allowed ) ){
			return TRUE;
		}
		if ( self::_testType( self::TYPE_O_STRING, $actual, $null_allowed ) ){
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	test if specified object is integer or integer object
	 *	
	 *	@param mixed $actual             data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function isInteger( $actual, $null_allowed = FALSE )
	{
		if ( self::_testType( self::TYPE_P_INTEGER, $actual, $null_allowed ) ){
			return TRUE;
		}
		if ( self::_testType( self::TYPE_O_INTEGER, $actual, $null_allowed ) ){
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	test if specified object is float or float object
	 *	
	 *	@param mixed $actual             data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function isFloat( $actual, $null_allowed = FALSE )
	{
		if ( self::_testType( self::TYPE_P_FLOAT, $actual, $null_allowed ) ){
			return TRUE;
		}
		if ( self::_testType( self::TYPE_O_FLOAT, $actual, $null_allowed ) ){
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	test if specified object is float or float object
	 *	
	 *	@param mixed $actual             data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function isBoolean( $actual, $null_allowed = FALSE )
	{
		if ( self::_testType( self::TYPE_P_BOOL, $actual, $null_allowed ) ){
			return TRUE;
		}
		if ( self::_testType( self::TYPE_O_BOOL, $actual, $null_allowed ) ){
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	test if specified object is array
	 *	
	 *	@param mixed $actual             data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function isArray( $actual, $null_allowed = FALSE )
	{
		if ( self::_testType( self::TYPE_P_ARRAY, $actual, $null_allowed ) ){
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	test if specified object is array or vector object
	 *	
	 *	@param mixed $actual             data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function isVector( $actual, $null_allowed = FALSE )
	{
		if ( self::_testType( self::TYPE_P_ARRAY, $actual, $null_allowed ) ){
			return TRUE;
		}
		if ( self::_testType( self::TYPE_O_VECTOR, $actual, $null_allowed ) ){
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	test if specified object is array or hashmap object
	 *	
	 *	@param mixed $actual             data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function isHashMap( $actual, $null_allowed = FALSE )
	{
		if ( self::_testType( self::TYPE_P_ARRAY, $actual, $null_allowed ) ){
			return TRUE;
		}
		if ( self::_testType( self::TYPE_O_HASHMAP, $actual, $null_allowed ) ){
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	test if specified object is array or properties object
	 *	
	 *	@param mixed $actual             data to check
	 *	@param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
	 *	
	 *	@return string        passed type
	 */
	public static function isProperties( $actual, $null_allowed = FALSE )
	{
		if ( self::_testType( self::TYPE_P_ARRAY, $actual, $null_allowed ) ){
			return TRUE;
		}
		if ( self::_testType( self::TYPE_O_PROPERTIES, $actual, $null_allowed ) ){
			return TRUE;
		}
		return FALSE;
	}

}

