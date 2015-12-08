<?php
/**
* Framework Exception Stack
*
* PHP version 5
*
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_FrameworkExceptionStack extends Charcoal_Object
{
    private $_stack;

    /*
     *    Construct object
     *
     */
    public function __construct()
    {
        $this->_stack = array();
    }

    /*
     *  Singleton instance
     */
    public static function getInstance()
    {
        static $singleton_;
        if ( $singleton_ == null ){
            $singleton_ = new Charcoal_FrameworkExceptionStack();
        }
        return $singleton_;
    }

    /**
     *    Check if the collection is empty
     *
     *    @return bool        TRUE if this collection has no elements, FALSE otherwise
     */
    public function isEmpty()
    {
        return count($this->_stack) === 0;
    }

    /*
     *  Add exception
     *
     * @param Exception $e               Exception to add
     */
    public static function push( Exception $e )
    {
        // Get singleton instace
        $ins = self::getInstance();

        // Just add exception
        array_push( $ins->_stack, $e );
    }

    /**
     *  Get top exception
     *
     * @return Exception             Exception
     */
    public static function pop()
    {
        // Get singleton instace
        $ins = self::getInstance();

        return array_pop( $ins->_stack );
    }

    /**
     *  String expression of this object
     *
     * @return string
     */
    public function toString()
    {
        return implode( ",", $this->_stack );
    }
}
