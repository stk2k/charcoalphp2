<?php
/**
* parameter validate trait
*
* PHP version 5
*
* @package    traits
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
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
    const TYPE_O_EXCEPTION2  = 'Throwable';
    const TYPE_O_OBJECT      = 'Charcoal_Object';
    const TYPE_O_FILE        = 'Charcoal_File';
    const TYPE_O_DTO         = 'Charcoal_DTO';

    // fraework types
    const TYPE_F_SANDBOX     = 'Charcoal_Sandbox';

    /**
     * validate if the value type seems to be boolean
     *
     * @param mixed $value            value to validate
     *
     * @return bool                   TRUE if the value type is boolean, FALSE otherwise
     */
    public static function is_bool( $value )
    {
        return $value === TRUE || $value === FALSE;
    }

    /**
     *    test a parameter
     *
     *    @param string $type            type name to validate
     *    @param mixed $actual           data to validate
     *
     *    @return string|boolean     If the test passes, it returns type name. Otherwise, returns FALSE.
     */
    private static function _testType( $type, $actual )
    {
        switch( $type ){
        case 'string':        return is_string( $actual ) ? 'string' : FALSE;
        case 'array':        return is_array( $actual ) ? 'array' : FALSE;
        case 'int':            return is_numeric( $actual ) ? 'integer' : FALSE;
        case 'integer':        return is_numeric( $actual ) ? 'integer' : FALSE;
        case 'float':        return is_numeric( $actual ) ? 'float' : FALSE;
        case 'bool':        return Charcoal_ScalarTrait::is_bool( $actual, TRUE ) ? 'boolean' : FALSE;
        case 'boolean':        return Charcoal_ScalarTrait::is_bool( $actual, TRUE ) ? 'boolean' : FALSE;
        case 'resource':    return is_resource($actual) ? 'resource' : FALSE;
        case 'object':        return is_object($actual) ? 'object' : FALSE;
        default:
            return ( $actual instanceof $type ) ? $type : FALSE;
        }
    }

    /**
     *    validate a parameter
     *
     *    @param int $key                parameter id
     *    @param string $type            type name to validate
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     *
     *    @return string        passed type
     */
    public static function validateType( $key, $type, $actual, $null_allowed = FALSE )
    {
        if ( $null_allowed && $actual === NULL )    return 'NULL';

        $ret = self::_testType( $type, $actual );
        if ( $ret ){
            return $ret;
        }
        list( $file, $line ) = Charcoal_System::caller(2);
        _throw( new Charcoal_ParameterException( $file, $line, $key, $type, $actual ) );
        return '';
    }

    /**
     *    validate a parameter
     *
     *    @param int $key                parameter id
     *    @param mixed $types            type list to validate
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     *
     * @return void
     */
    public static function validateTypes( $key, $types, $actual, $null_allowed = FALSE )
    {
        if ( $null_allowed && $actual === NULL )    return;

        foreach( $types as $type ){
            $ret = self::_testType( $type, $actual );
            if ( $ret ){
                return;
            }
        }
        list( $file, $line ) = Charcoal_System::caller(2);
        _throw( new Charcoal_ParameterException( $file, $line, $key, $types, $actual ) );
    }

    /**
     *    validate a parameter if its type is string object or string
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateString( $key, $actual, $null_allowed = FALSE )
    {
        self::validateTypes( $key, array( self::TYPE_P_STRING, self::TYPE_O_STRING ), $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is integer object or int
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateInteger( $key, $actual, $null_allowed = FALSE )
    {
        self::validateTypes( $key, array( self::TYPE_P_INTEGER, self::TYPE_O_INTEGER ), $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is float object or float
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateFloat( $key, $actual, $null_allowed = FALSE )
    {
        self::validateTypes( $key, array( self::TYPE_P_FLOAT, self::TYPE_O_FLOAT ), $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is boolean object or boolean
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateBoolean( $key, $actual, $null_allowed = FALSE )
    {
        self::validateTypes( $key, array( self::TYPE_P_BOOL, self::TYPE_O_BOOLEAN ), $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is PHP string
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateRawString( $key, $actual, $null_allowed = FALSE )
    {
        self::validateType( $key, self::TYPE_P_STRING, $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is PHP integer
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateRawInteger( $key, $actual, $null_allowed = FALSE )
    {
        self::validateType( $key, self::TYPE_P_INTEGER, $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is PHP float
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateRawFloat( $key, $actual, $null_allowed = FALSE )
    {
        self::validateType( $key, self::TYPE_P_FLOAT, $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is PHP bool
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateRawBool( $key, $actual, $null_allowed = FALSE )
    {
        self::validateType( $key, self::TYPE_P_BOOL, $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is PHP array
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateRawArray( $key, $actual, $null_allowed = FALSE )
    {
        self::validateType( $key, self::TYPE_P_ARRAY, $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is array object
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateVector( $key, $actual, $null_allowed = FALSE )
    {
        self::validateTypes( $key, array( self::TYPE_P_ARRAY, self::TYPE_O_VECTOR ), $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is hash map object
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateHashMap( $key, $actual, $null_allowed = FALSE )
    {
        self::validateTypes( $key, array( self::TYPE_P_ARRAY, self::TYPE_O_HASHMAP ), $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is array or HashMap or DTO
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateHashMapOrDTO( $key, $actual, $null_allowed = FALSE )
    {
        self::validateTypes( $key, array( self::TYPE_P_ARRAY, self::TYPE_O_HASHMAP, self::TYPE_O_DTO ), $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is DTO
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateDTO( $key, $actual, $null_allowed = FALSE )
    {
        self::validateType( $key, self::TYPE_O_DTO, $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is a properties object
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateProperties( $key, $actual, $null_allowed = FALSE )
    {
        self::validateTypes( $key, array( self::TYPE_P_ARRAY, self::TYPE_O_PROPERTIES ), $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is object path
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateObjectPath( $key, $actual, $null_allowed = FALSE )
    {
        self::validateType( $key, self::TYPE_O_OBJECTPATH, $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is config
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateConfig( $key, $actual, $null_allowed = FALSE )
    {
        self::validateType( $key, self::TYPE_O_CONFIG, $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is exception object
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateException( $key, $actual, $null_allowed = FALSE )
    {
        self::validateTypes( $key, array( self::TYPE_O_EXCEPTION, self::TYPE_O_EXCEPTION2 ), $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is resource object
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateResource( $key, $actual, $null_allowed = FALSE )
    {
        self::validateType( $key, self::TYPE_P_RESOURCE, $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is object
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateRawObject( $key, $actual, $null_allowed = FALSE )
    {
        self::validateType( $key, self::TYPE_P_OBJECT, $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is charcoal object
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateCharcoalObject( $key, $actual, $null_allowed = FALSE )
    {
        self::validateType( $key, self::TYPE_O_OBJECT, $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is file object
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateFile( $key, $actual, $null_allowed = FALSE )
    {
        self::validateType( $key, self::TYPE_O_FILE, $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is object
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateScalar( $key, $actual, $null_allowed = FALSE )
    {
        static $types;
        if ( !$types ){
            $types = array(
                        // primitive types
                        self::TYPE_P_STRING, self::TYPE_P_INTEGER, self::TYPE_P_FLOAT, self::TYPE_P_BOOL,
                        // object types
                        self::TYPE_O_STRING, self::TYPE_O_INTEGER, self::TYPE_O_FLOAT, self::TYPE_O_BOOLEAN,
                    );
        }
        self::validateTypes( $key, $types, $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is string or object
     *
     *    @param int $key                parameter id
     *    @param string $type            class or interface name for object
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateStringOrObject( $key, $type, $actual, $null_allowed = FALSE )
    {
        self::validateTypes( $key, array( self::TYPE_P_STRING, self::TYPE_O_STRING, $type ), $actual, $null_allowed );
    }

    /**
     *    validate a parameter if its type is string or object path
     *
     *    @param int $key                parameter id
     *    @param mixed $actual           data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateStringOrObjectPath( $key, $actual, $null_allowed = FALSE )
    {
        self::validateTypes( $key, array( self::TYPE_P_STRING, self::TYPE_O_STRING, self::TYPE_O_OBJECTPATH ), $actual, $null_allowed );
    }

    /**
     *    validate a parameter if specified object is sandbox object
     *
     *    @param int $key                  parameter id
     *    @param mixed $actual             data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateSandbox( $key, $actual, $null_allowed = FALSE )
    {
        self::validateType( $key, self::TYPE_F_SANDBOX, $actual, $null_allowed );
    }

    /**
     *    validate a parameter if the object is of this class or has this class as one of its parents
     *
     *    @param int $key              parameter id
     *    @param string $class_name    class name of the object should extend from
     *    @param mixed $actual         data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateIsA( $key, $class_name, $actual, $null_allowed = FALSE )
    {
        if ( $null_allowed && $actual === NULL )    return;

        if ( $actual instanceof $class_name ){
            return;
        }
        list( $file, $line ) = Charcoal_System::caller(1);
        _throw( new Charcoal_ParameterException( $file, $line, $key, $class_name, $actual ) );
    }

    /**
     *    validate a parameter if specified object implements an interface
     *
     *    @param int $key                  parameter id
     *    @param string $interface_name   interface name of the object should implement
     *    @param mixed $actual             data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     */
    public static function validateImplements( $key, $interface_name, $actual, $null_allowed = FALSE )
    {
        if ( $null_allowed && $actual === NULL )    return;

        if ( $actual instanceof $interface_name ){
            return;
        }
        list( $file, $line ) = Charcoal_System::caller(1);
        _throw( new Charcoal_ParameterException( $file, $line, $key, $interface_name, $actual ) );
    }

    /**
     *    test if specified object is string or string object
     *
     *    @param mixed $actual             data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     *
     *    @return string        passed type
     */
    public static function isString( $actual, $null_allowed = FALSE )
    {
        if ( $null_allowed && $actual === NULL )    return 'NULL';

        if ( self::_testType( self::TYPE_P_STRING, $actual ) ){
            return TRUE;
        }
        if ( self::_testType( self::TYPE_O_STRING, $actual ) ){
            return TRUE;
        }
        return FALSE;
    }

    /**
     *    test if specified object is integer or integer object
     *
     *    @param mixed $actual             data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     *
     *    @return string        passed type
     */
    public static function isInteger( $actual, $null_allowed = FALSE )
    {
        if ( $null_allowed && $actual === NULL )    return 'NULL';

        if ( self::_testType( self::TYPE_P_INTEGER, $actual ) ){
            return TRUE;
        }
        if ( self::_testType( self::TYPE_O_INTEGER, $actual ) ){
            return TRUE;
        }
        return FALSE;
    }

    /**
     *    test if specified object is float or float object
     *
     *    @param mixed $actual             data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     *
     *    @return string        passed type
     */
    public static function isFloat( $actual, $null_allowed = FALSE )
    {
        if ( $null_allowed && $actual === NULL )    return 'NULL';

        if ( self::_testType( self::TYPE_P_FLOAT, $actual ) ){
            return TRUE;
        }
        if ( self::_testType( self::TYPE_O_FLOAT, $actual ) ){
            return TRUE;
        }
        return FALSE;
    }

    /**
     *    test if specified object is float or float object
     *
     *    @param mixed $actual             data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     *
     *    @return string        passed type
     */
    public static function isBoolean( $actual, $null_allowed = FALSE )
    {
        if ( $null_allowed && $actual === NULL )    return 'NULL';

        if ( self::_testType( self::TYPE_P_BOOL, $actual ) ){
            return TRUE;
        }
        if ( self::_testType( self::TYPE_O_BOOLEAN, $actual ) ){
            return TRUE;
        }
        return FALSE;
    }

    /**
     *    test if specified object is array
     *
     *    @param mixed $actual             data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     *
     *    @return string        passed type
     */
    public static function isArray( $actual, $null_allowed = FALSE )
    {
        if ( $null_allowed && $actual === NULL )    return 'NULL';

        if ( self::_testType( self::TYPE_P_ARRAY, $actual ) ){
            return TRUE;
        }
        return FALSE;
    }

    /**
     *    test if specified object is array or vector object
     *
     *    @param mixed $actual             data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     *
     *    @return string        passed type
     */
    public static function isVector( $actual, $null_allowed = FALSE )
    {
        if ( $null_allowed && $actual === NULL )    return 'NULL';

        if ( self::_testType( self::TYPE_P_ARRAY, $actual ) ){
            return TRUE;
        }
        if ( self::_testType( self::TYPE_O_VECTOR, $actual ) ){
            return TRUE;
        }
        return FALSE;
    }

    /**
     *    test if specified object is array or hashmap object
     *
     *    @param mixed $actual             data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     *
     *    @return string        passed type
     */
    public static function isHashMap( $actual, $null_allowed = FALSE )
    {
        if ( $null_allowed && $actual === NULL )    return 'NULL';

        if ( self::_testType( self::TYPE_P_ARRAY, $actual ) ){
            return TRUE;
        }
        if ( self::_testType( self::TYPE_O_HASHMAP, $actual ) ){
            return TRUE;
        }
        return FALSE;
    }

    /**
     *    test if specified object is array or properties object
     *
     *    @param mixed $actual             data to validate
     *    @param boolean $null_allowed   if TRUE, NULL value will be accepted. FALSE otherwise.
     *
     *    @return string        passed type
     */
    public static function isProperties( $actual, $null_allowed = FALSE )
    {
        if ( $null_allowed && $actual === NULL )    return 'NULL';

        if ( self::_testType( self::TYPE_P_ARRAY, $actual ) ){
            return TRUE;
        }
        if ( self::_testType( self::TYPE_O_PROPERTIES, $actual ) ){
            return TRUE;
        }
        return FALSE;
    }

}

