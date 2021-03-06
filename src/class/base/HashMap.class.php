<?php
/**
* 連想配列クラス
*
* キー、値ともに型は限定しない
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_HashMap extends Charcoal_Collection implements ArrayAccess, IteratorAggregate
{
    /**
     * Retrieve default value
     *
     * @return Charcoal_HashMap        default value
     */
    public static function defaultValue()
    {
        return new self();
    }

    /**
     *    get key list
     */
    public function getKeys() {
        return array_keys( $this->values );
    }

    /**
     *  check if specified key is in the list
     *
     * @param mixed $key
     *
     * @return bool
     */
    public function keyExists( $key )
    {
        return array_key_exists( $key, $this->values );
    }

    /**
     *    Check if the collection is empty
     *
     *    @return bool        TRUE if this collection has no elements, FALSE otherwise
     */
    public function isEmpty()
    {
        return count( $this->values ) === 0;
    }

    /**
     *    Get an element value
     *
     * @param mixed $key
     *
     * @return mixed|NULL
     */
    public function get( $key )
    {
        return $this->offsetGet( $key );
    }

    /**
     *    Get all values with keys
     *
     * @return array
     */
    public function getAll()
    {
        return $this->values;
    }

    /**
     *    update an element value
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function set( $key, $value )
    {
        $this->offsetSet( $key, $value );
    }

    /**
     *    Get an element value
     *
     * @param mixed $key
     *
     * @return mixed|NULL
     */
    public function __get( $key )
    {
        return $this->offsetGet( $key );
    }

    /**
     *    Set an element value
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function __set( $key, $value )
    {
        $this->offsetSet( $key, $value );
    }

    /**
     *    ArrayAccess interface : offsetGet() implementation
     *
     * @param mixed $offset
     *
     * @return mixed|NULL
     */
    public function offsetGet($offset)
    {
        if ( is_object($offset) ){
            $offset = $offset->__toString();
        }
        return isset($this->values[ $offset ]) ? $this->values[ $offset ] : NULL;
    }

    /**
     *    ArrayAccess interface : offsetSet() implementation
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if ( is_object($offset) ){
            $offset = $offset->__toString();
        }
        $this->values[ $offset ] = $value;
    }

    /**
     *    ArrayAccess interface : offsetExists() implementation
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->values[$offset]);
    }

    /**
     *    ArrayAccess interface : offsetUnset() implementation
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->values[$offset]);
    }

    /**
     *    Countable interface: count() implementation
     */
    public function count()
    {
        return count( $this->values );
    }

    /**
     *    Set all array elements
     *
     *    @param array|Charcoal_Vector $array   array data to set
     */
    public function setArray( $array )
    {
        $this->values = $array;
    
        if ($array instanceof Charcoal_Vector){
            $this->values = $array->toArray();
        }
        else if ( is_array($array) ){
            $this->values = $array;
        }
        else{
            _throw( new Charcoal_InvalidArgumentException($array) );
        }
    }

    /**
     *    Set all hashmap elements
     *
     *    @param array|Charcoal_HashMap $map   hashmap data to set
     */
    public function setHashMap( $map )
    {
        if ($map instanceof Charcoal_HashMap){
            $this->values = $map->toArray();
        }
        else if ( is_array($map) ){
            $this->values = $map;
        }
        else{
            _throw( new Charcoal_InvalidArgumentException($map) );
        }
    }

    /**
     *    Merge with array
     *
     *    @param array $array            array data to merge
     *    @param boolean $overwrite      TRUE means overwrite if the original element exists
     */
    public function mergeArray( $array, $overwrite = TRUE )
    {
//        Charcoal_ParamTrait::validatRawArray( 1, $array );
//        Charcoal_ParamTrait::validateBoolean( 2, $overwrite );

        $overwrite = ub($overwrite);

        if ( $overwrite ){
            foreach( $array as $key => $value ){
                $this->offsetSet( $key, $value );
            }
        }
    }

    /**
     *    Merge with hashmap
     *
     *    @param array $array            hash map data to merge
     *    @param boolean $overwrite      TRUE means overwrite if the original element exists
     */
    public function mergeHashMap( $map, $overwrite  = TRUE )
    {
//        Charcoal_ParamTrait::validateHashMap( 1, $map );
//        Charcoal_ParamTrait::validateBoolean( 2, $overwrite );

        $overwrite = ub($overwrite);

        if ( $overwrite ){
            foreach( $map as $key => $value ){
                $this->offsetSet( $key, $value );
            }
        }
    }

    /**
     *  Get element value as string
     *
     * @param string $key             Key string to get
     * @param string $default_value   default value
     * @param string $encoding        charcter encoding
     *
     * @return Charcoal_String
     */
    public function getString( $key, $default_value = NULL, $encoding = NULL )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validateString( 2, $default_value, TRUE );

        return Charcoal_ArrayTrait::getString( $this->values, $key, $default_value, $encoding );
    }

    /**
     *  Get element value as array
     *
     * @param string $key            Key string to get
     * @param array $default_value   default value
     *
     * @return Charcoal_Vector
     */
    public function getArray( $key, $default_value = NULL )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validateVector( 2, $default_value, TRUE );

        return Charcoal_ArrayTrait::getArray( $this->values, $key, $default_value );
    }

    /**
     *  Get element value as associative array
     *
     * @param string $key            Key string to get
     * @param array $default_value   default value
     *
     * @return Charcoal_HashMap
     */
    public function getHashMap( $key, $default_value = NULL )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validateHashMap( 2, $default_value, TRUE );

        return Charcoal_ArrayTrait::getHashMap( $this->values, $key, $default_value );
    }

    /**
     *  Get element value as boolean
     *
     * @param string $key           Key string to get
     * @param bool $default_value   default value
     *
     * @return Charcoal_Boolean
     */
    public function getBoolean( $key, $default_value = NULL )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validateBoolean( 2, $default_value, TRUE );

        return Charcoal_ArrayTrait::getBoolean( $this->values, $key, $default_value );
    }

    /**
     *  Get element value as integer
     *
     * @param string $key          Key string to get
     * @param int $default_value   default value
     *
     * @return Charcoal_Integer
     */
    public function getInteger( $key, $default_value = NULL )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validateInteger( 2, $default_value, TRUE );

        return Charcoal_ArrayTrait::getInteger( $this->values, $key, $default_value );
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

        return Charcoal_ArrayTrait::getFloat( $this->values, $key, $default_value );
    }

    /**
     * convert to array
     *
     * @return array
     */
    public function toArray()
    {
        if ( is_array($this->values) ){
            return $this->values;
        }
        return array_diff( $this->values, array() );
    }

    /**
     *    make string glued by a delimiter
     */
    public function implodeAssoc( $glue = ',' )
    {
        return Charcoal_System::implodeAssoc( $glue, $this->values );
    }

    /**
     *  String expression of this object
     *
     * @return string
     */
    public function toString()
    {
        $b = '';
        foreach ($this->values as $key => $value) {
            if ( !empty($b) ){
                $b .= '/';
            }
            $key = Charcoal_System::toString($key);
            $value = Charcoal_System::toString($value);
            $b .= "$key=$value";
        }
        return "[$b]";
    }

}

