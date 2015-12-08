<?php
/**
* Interface of collection classes
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_ICollection
{
    /**
     *    Get all values with keys
     *
     * @return array
     */
    public function getAll();

    /**
     *    Remove all elements
     */
    public function clear();

    /**
     *    Applies a callback to all elements
     *
     * @return array
     */
    public function map( $callable );

}

