<?php
/**
* Class which contains config propery set
*
* PHP version 5
*
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ConfigPropertySet extends Charcoal_HashMap
{
    private $env;

    /**
     *  Constructor
     *
     * @param Charcoal_IEnvironment $env
     * @param array $values
     */
    public function __construct( $env, $values = NULL )
    {
//        Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_IEnvironment', $env );
//        Charcoal_ParamTrait::validatRawArray( 2, $values, TRUE );

        $this->env = $env;

        parent::__construct( $values );
    }

    /**
     *  Get sandvox
     */
    public function getSandbox()
    {
        return $this->sandbox;
    }

    /**
     *  Get child section
     *
     * @param Charcoal_String|string $section
     *
     * @return Charcoal_ConfigPropertySet
     */
    public function getSection( $section )
    {
//        Charcoal_ParamTrait::validateString( 1, $section );

        $value = parent::get( $section );

/*
        if ( !is_array($value) ){
            _throw( new Charcoal_ConfigSectionNotFoundException( $section ) );
        }
*/

        return new Charcoal_ConfigPropertySet( $this->env, $value );
    }

    /**
     * Get as string value
     *
     * @param string $key             key string for hash map
     * @param string $default_value   default value
     * @param bool $process_macro     if TRUE, value will be replaced by keywords, FALSE otherwise
     * @param string $encoding        charcter encoding
     *
     * @return Charcoal_String
     */
    public function getString( $key, $default_value = NULL, $process_macro = FALSE, $encoding = NULL )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validateString( 2, $default_value, TRUE );
//        Charcoal_ParamTrait::validateBoolean( 3, $process_macro );

        $key = us($key);
        $value = parent::getString( $key, $default_value, $encoding );

        if ( $process_macro ){
            $value = s( Charcoal_ResourceLocator::processMacro( $this->env, $value ) );
        }

        return $value;
    }

    /**
     * Get as json value
     *
     * @param string $key             key string for hash map
     * @param string $default_value   default value
     * @param bool $process_macro     if TRUE, value will be replaced by keywords, FALSE otherwise
     * @param string $encoding        charcter encoding
     *
     * @return Charcoal_String
     */
    public function getJson( $key, $default_value = NULL, $process_macro = FALSE, $encoding = NULL )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validateString( 2, $default_value, TRUE );
//        Charcoal_ParamTrait::validateBoolean( 3, $process_macro );

        $key = us($key);
        $value = parent::getString( $key, $default_value, $encoding );

        log_debug( "debug", "caller: " . print_r(Charcoal_System::caller(),true) );
        log_debug( "debug", "json_decode: $value" );

        $decoded = json_decode( us($value), true );

        if ( $decoded === NULL ){
            _throw( new Charcoal_JsonDecodingException($value) );
        }

        log_debug( "debug", "decoded: " . print_r($decoded,true) );

        if ( $process_macro ){
            $decoded = Charcoal_ResourceLocator::processMacro( $this->env, $decoded );
        }

        return $decoded;
    }

    /**
     * Get as array value
     *
     * @param string $key             key string for hash map
     * @param array $default_value   default value
     * @param bool $process_macro     if TRUE, value will be replaced by keywords, FALSE otherwise
     *
     * @return Charcoal_Vector
     */
    public function getArray( $key, $default_value = NULL, $process_macro = FALSE )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validateVector( 2, $default_value, TRUE );
//        Charcoal_ParamTrait::validateBoolean( 3, $process_macro );

        $key = us($key);
        $items = parent::getArray( $key, $default_value );

        if ( $items === NULL ){
            return NULL;
        }

        // remove empty entry
        foreach( $items as $key => $item ){
            if ( empty($item) )    unset($items[$key]);
        }

        if ( $process_macro ){
            $items = Charcoal_ResourceLocator::processMacro( $this->env, $items );
        }

        return v($items);
    }

    /**
     * Get as hash map value
     *
     * @param string $key             key string for hash map
     * @param array $default_value   default value
     * @param bool $process_macro     if TRUE, value will be replaced by keywords, FALSE otherwise
     *
     * @return Charcoal_HashMap
     */
    public function getHashMap( $key, $default_value = NULL, $process_macro = FALSE )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validateVector( 2, $default_value, TRUE );
//        Charcoal_ParamTrait::validateBoolean( 3, $process_macro );

        $key = us($key);
        $items = parent::getHashMap( $key, $default_value );

        if ( $items === NULL ){
            return NULL;
        }

        // remove empty entry
        foreach( $items as $key => $item ){
            if ( empty($item) )    unset($items[$key]);
        }

        if ( $process_macro ){
            $items = Charcoal_ResourceLocator::processMacro( $this->env, $items );
        }

        return m($items);
    }

    /**
     * Get as boolean value
     *
     * @param string $key             key string for hash map
     * @param bool $default_value   default value
     *
     * @return Charcoal_Boolean
     */
    public function getBoolean( $key, $default_value = NULL )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validateBoolean( 2, $default_value, TRUE );

        $key = us($key);
        return parent::getBoolean( $key, $default_value );
    }

    /**
     * Get as integer value
     *
     * @param string $key             key string for hash map
     * @param int $default_value   default value
     *
     * @return Charcoal_Integer
     */
    public function getInteger( $key, $default_value = NULL )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validateInteger( 2, $default_value, TRUE );

        $key = us($key);
        return parent::getInteger( $key, $default_value );
    }

    /**
     *  Get element value as float
     *
     * @param string $key            Key string to get
     * @param float $default_value   default value
     *
     * @return Charcoal_Float
     */
    public function getFloat( $key, $default_value = NULL )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validatFloat( 2, $default_value, TRUE );

        $key = us($key);
        return parent::getFloat( $key, $default_value );
    }

    /**
     *  Get element value as file size
     *
     * @param string $key            Key string to get
     * @param string $default_value   default value
     *
     * @return integer
     */
    public function getSize( $key, $default_value = NULL )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validateString( 2, $default_value, TRUE );

        $key = us($key);
        $value = parent::getString( $key, $default_value );

        return Charcoal_MemoryUtil::getByteSizeFromString( $value );
    }

}

