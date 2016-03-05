<?php
/**
* Response base class
*
* PHP version 5
*
* @package    objects.responses
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_AbstractResponse extends Charcoal_CharcoalComponent implements Charcoal_IResponse
{
    private $values;
    private $filters;

    /**
     *    Construct object
     */
    public function __construct()
    {
        parent::__construct();
        $this->values = array();
    }

    /**
     *    Applies a callback to all elements
     *
     * @return array
     */
    public function map( $callable )
    {
        $this->values = array_map( $callable, $this->values );
        return $this;
    }

    /**
     *  Get all keys included in this container
     *
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->values);
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
     *  check if specified key is in the list
     */
    public function keyExists( $key )
    {
        $key = us($key);
        return array_key_exists($key,$this->values);
    }

    /**
     *    Iterator interface: rewind() implementation
     */
    public function rewind() {
        reset($this->values);
    }

    /**
     *    Iterator interface: current() implementation
     */
    public function current() {
        $var = current($this->values);
        return $var;
    }

    /**
     *    Iterator interface: key() implementation
     */
    public function key() {
        $var = key($this->values);
        return $var;
    }

    /**
     *    Iterator interface: next() implementation
     */
    public function next() {
        $var = next($this->values);
        return $var;
    }

    /**
     *    Iterator interface: valid() implementation
     */
    public function valid() {
        $var = $this->current() !== false;
        return $var;
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
     *  Get value from container if specified key is included, otherwise returns NULL.
     *
     * @param string $key   Key of the value
     *
     * @return mixed
     */
    public function get( $key )
    {
        return $this->offsetGet( $key );
    }

    /**
     *    Get an element value
     */
    public function __get( $key )
    {
        return $this->offsetGet( $key );
    }

    /**
     *    Set an element value
     */
    public function __set( $key, $value )
    {
        $this->offsetSet( $key, $value );
    }

    /**
     *    ArrayAccess interface : offsetGet() implementation
     */
    public function offsetGet($key)
    {
//        Charcoal_ParamTrait::validateString( 1, $key );

        $key = us($key);
        return isset($this->values[ $key ]) ? $this->values[ $key ] : NULL;
    }

    /**
     *    ArrayAccess interface : offsetSet() implementation
     */
    public function offsetSet($key, $value)
    {
        $this->set( $key, $value, FALSE );
    }

    /**
     *    ArrayAccess interface : offsetExists() implementation
     */
    public function offsetExists($key)
    {
//        Charcoal_ParamTrait::validateString( 1, $key );

        $key = us($key);
        return isset($this->values[$key]);
    }

    /**
     *    ArrayAccess interface : offsetUnset() implementation
     */
    public function offsetUnset($key)
    {
//        Charcoal_ParamTrait::validateString( 1, $key );

        $key = us($key);
        unset($this->values[$key]);
    }

    /**
     *    Countable interface: count() implementation
     */
    public function count()
    {
        return count( $this->values );
    }

    /**
     *  Set value in container.
     *
     * @param Charcoal_String $key   Key of the value
     * @param mixed $value   Value to set
     * @param Charcoal_Boolean $skipfilters   If TRUE, skip all registered filters
     *
     * @return mixed
     */
    public function set( $key, $value, $skipfilters = FALSE )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validateRawBool( 2, $skipfilters );

        $skipfilters = b($skipfilters);

        if ( !$skipfilters->isTrue() ){
            $value = $this->_applyAllFilters($value);
        }
        else{
            log_debug( "system,debug,response", "skipped applying all filters: $key" );
        }
        $this->values[us($key)] = $value;
    }

    /**
     *    Set all array elements
     *
     *    @param array $array   array data to set
     */
    public function setArray( $array )
    {
        Charcoal_ParamTrait::validatRawArray( 1, $array );

        $this->values = $array;
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
//        Charcoal_ParamTrait::validateRawBool( 2, $overwrite );

        $overwrite = ub($overwrite);

        if ( $overwrite ){
            foreach( $array as $key => $value ){
                $this->values[$key] = $value;
            }
        }
    }

    /**
     *    Set all elements in a HashMap object into container. All of container elements will be overwrited.
     *
     * @param Charcoal_HashMap $map   HashMap object to set
     */
    public function setHashMap( $map )
    {
//        Charcoal_ParamTrait::validateHashMap( 1, $map );

        $this->values = is_array($map) ? $map : $map->getAll();
    }

    /**
     *    Merge all elements in a HashMap object into container
     *
     * @param Charcoal_HashMap $data   Properties object to merge
     * @param Charcoal_Boolean $overwrite   If TRUE, container values will be overwrited by properties data.
     */
    public function mergeHashMap( $map, $overwrite = TRUE )
    {
//        Charcoal_ParamTrait::validateHashMap( 1, $map );
//        Charcoal_ParamTrait::validateRawBool( 2, $overwrite );

        $overwrite = ub($overwrite);

        if ( $overwrite ){
            foreach( $map as $key => $value ){
                $this->values[$key] = $value;
            }
        }
    }

    /**
     * Copy parameters from request object. All of container elements will be overwrited.
     *
     * @param Charcoal_IRequest $request   Request object to set
     */
    public function setRequest( $request )
    {
//        Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_IRequest', $request );

        $this->values = $request->getAll();
    }

    /**
     *    Merge all elements in a HashMap object into container
     *
     * @param Charcoal_IRequest $request   Request object to merge
     * @param Charcoal_Boolean $overwrite   If TRUE, container values will be overwrited by properties data.
     */
    public function mergeRequest( $request, $overwrite = TRUE )
    {
//        Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_IRequest', $request );
//        Charcoal_ParamTrait::validateRawBool( 2, $overwrite );

        $overwrite = ub($overwrite);

        if ( $overwrite ){
            foreach( $request as $key => $value ){
                $this->values[$key] = $value;
            }
        }
    }

    /**
     *  apply all filters
     *
     * @param mixed $value   Value to apply
     */
    private function _applyAllFilters( $value )
    {
        if ( !$this->filters ){
            $this->filters = array();

            $responsefilters = $this->getSandbox()->getProfile()->getArray( 'RESPONSE_FILTERS', array('strip_tags','html_escape') );
            if ( $responsefilters ){
                foreach( $responsefilters as $filter_name ){
                    if ( strlen($filter_name) === 0 )    continue;

                    $filter = $this->getSandbox()->createObject( $filter_name, 'response_filter', array(), 'Charcoal_IResponseFilter' );
                    $this->filters[] = $filter;
                }
            }
        }

        // apply all filters
        foreach( $this->filters as $filter ){
            $value = $filter->apply( $value );
        }

        return $value;
    }

    /*
     * Add response filter
     *
     * @param Charcoal_IResponseFilter $filter filter to add
     */
    public function addResponseFilter( $filter )
    {
//        Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_IResponseFilter', $filter );

        $this->filters[] = $filter;
    }

    /*
     * Remove response filter
     *
     * @param Charcoal_IResponseFilter $filter filter to remove
     */
    public function removeResponseFilter( $filter )
    {
//        Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_IResponseFilter', $filter );

        if ( $this->filters && is_array($this->filters) ){
            foreach( $this->filters as $key => $f ){
                if ( $f->equals($filter) ){
                    unnset( $this->filters[$key] );
                    return;
                }
            }
        }
    }

}

