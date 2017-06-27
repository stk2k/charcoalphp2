<?php
/**
* Interface of object
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IObject
{
    /**
     *    test equal objects
     *
     * @param mixed $object
     *
     * @return boolean   returns TRUE if this object is regarded as same object to target object.
     */
    public function equals( $object );
    
    /*
     *  String expression of this object
     *
     * @return string
     */
    public function __toString();

    /*
     *  String expression of this object
     *
     * @return string
     */
    public function toString();
}

