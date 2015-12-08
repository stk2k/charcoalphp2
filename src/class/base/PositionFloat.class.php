<?php
/**
* Rectangle
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_PositionFloat extends Charcoal_Object
{
    private $_left;
    private $_top;

    /*
     *    Construct
     */
    public function __construct( Charcoal_Float $left, Charcoal_Float $top )
    {
        parent::__construct();

        $this->_left     = uf($left);
        $this->_top      = uf($top);
    }

    /*
     *    Get Left
     */
    public function getLeft()
    {
        return $this->_left;
    }

    /*
     *    Set Left
     */
    public function setLeft( Charcoal_Float $left )
    {
        $this->_left = uf($left);
    }

    /*
     *    Get Top
     */
    public function getTop()
    {
        return $this->_top;
    }

    /*
     *    Set Top
     */
    public function setTop( Charcoal_Float $top )
    {
        $this->_top = uf($top);
    }

}

