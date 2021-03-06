<?php
/**
* base class for wrppert context
*
* PHP version 5
*
* @package    component.charcoal.db.context
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_AbstractWrapperContext extends Charcoal_Object
{
    private $_context;

    /**
     *  Constructor
     *
     * @param Charcoal_QueryContext $context
     */
    public function __construct( Charcoal_QueryContext $context )
    {
        $this->_context = $context;
    }

    /**
     *  get wrapped context
     *
     * @return Charcoal_QueryContext    wrapped context
     */
    public function getContext()
    {
        return $this->_context;
    }

}

