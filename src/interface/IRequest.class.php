<?php
/**
* interface of request object
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IRequest extends Charcoal_ICharcoalObject, Iterator, ArrayAccess
{
    /**
     *  Retrieve current procedure path
     */
    public function getProcedurePath();

    /**
     *  Get element value as string
     *
     * @param string $key             Key string to get
     * @param string $default_value   default value
     * @param string $encoding        charcter encoding
     *
     * @return Charcoal_String
     */
    public function getString( $key, $default_value = NULL, $encoding = NULL );

    /**
     * Get as json value
     *
     * @param string $key             key string for hash map
     * @param string $default_value   default value
     * @param string $encoding        charcter encoding
     *
     * @return Charcoal_String
     */
    public function getJson( $key, $default_value = NULL, $encoding = NULL );

    /**
     *  Get element value as array
     *
     * @param string $key            Key string to get
     * @param array $default_value   default value
     *
     * @return Charcoal_Vector
     */
    public function getArray( $key, $default_value = NULL );

    /**
     *  Get element value as associative array
     *
     * @param string $key            Key string to get
     * @param array $default_value   default value
     *
     * @return Charcoal_HashMap
     */
    public function getHashMap( $key, $default_value = NULL );

    /**
     *  Get element value as boolean
     *
     * @param string $key           Key string to get
     * @param bool $default_value   default value
     *
     * @return Charcoal_Boolean
     */
    public function getBoolean( $key, $default_value = NULL );

    /**
     *  Get element value as integer
     *
     * @param string $key          Key string to get
     * @param int $default_value   default value
     *
     * @return Charcoal_Integer
     */
    public function getInteger( $key, $default_value = NULL );

    /**
     *  Get element value as float
     *
     * @param string $key            Key string to get
     * @param float $default_value   default value
     *
     * @return Charcoal_Float
     */
    public function getFloat( $key, $default_value = NULL );

    /**
     * convert to array
     *
     * @return array
     */
    public function toArray();

    /**
     *    Get an element value
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get( $key );

    /**
     *    Get all values with keys
     *
     * @return array
     */
    public function getAll();
}

