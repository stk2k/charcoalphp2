<?php
/**
* Most basic class in charcoalphp
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_Object
{
    /**
     *    Constructor
     */
    public function __construct()
    {
    }

    /**
     *    make hash code of this object
     *
     * @return string   unique string of this object
     */
    public function hash()
    {
        return spl_object_hash($this);
    }

    /**
     *    test equal objects
     *
     * @param mixed $object
     *
     * @return boolean   returns TRUE if this object is regarded as same object to target object.
     */
    public function equals( $object )
    {
        if ( !($object instanceof Charcoal_Object) ){
            return FALSE;
        }
        return spl_object_hash($this) === spl_object_hash($object);
    }

    /**
     *  Check if an object implements or extends target
     *
     * @param string|Charcoal_String $target
     *
     * @return boolean   returns TRUE if this object implements interface, or derived from target class.
     */
    public function isInstanceOf( $target )
    {
        return $this instanceof $target;
    }

    /**
     * Get class name
     *
     * @return string   class name
     */
    public function getClassName()
    {
        return get_class($this);
    }

    /**
     * Get class
     */
    public function getClass()
    {
        return new Charcoal_Class( get_class( $this ) );
    }

    /**
     *  String expression of this object
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();    // __toString() must return string type only!
    }

    /**
     *  String expression of this object
     *
     * @return string
     */
    public function toString()
    {
        return '[class=' . get_class($this) . ' hash=' . $this->hash() . ']';
    }
}

