<?php
/**
* base class for collection
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

abstract class Charcoal_Collection extends Charcoal_Object implements Countable, Charcoal_IUnboxable, IteratorAggregate
{
    protected $values;

    /*
     *    コンストラクタ
     */
    public function __construct( $values = array() )
    {
        parent::__construct();

        if ( $values ){
            if ( is_array($values) ){
                $this->values = $values;
            }
            else{
                _throw( new Charcoal_NonArrayException($values) );
            }
        }
        else{
            $this->values = array();
        }
    }

    /*
     *    要素数を取得
     */
    public function count()
    {
        return count( $this->values );
    }

    /*
     *    要素数を取得
     */
    public function size()
    {
        return count( $this->values );
    }

    /**
     *    unbox primitive value
     */
    public function unbox()
    {
        return $this->values;
    }

    /**
     *    Remove all elements
     */
    public function clear()
    {
        $this->values = array();
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
     *    IteratorAggregate interface: valid() implementation
     */
    public function getIterator()
    {
        return new ArrayIterator( $this->values );
    }

    /**
     *    Applies a callback to all elements
     *
     * @param callable $callable
     *
     * @return Charcoal_Collection
     */
    public function map( $callable )
    {
        $this->values = array_map( $callable, $this->values );
        return $this;
    }

}

