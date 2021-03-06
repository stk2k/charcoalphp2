<?php
/**
* Group by context for fluent interface
*
* PHP version 5
*
* @package    component.charcoal.db.context
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_GroupByContext extends Charcoal_AbstractWrapperContext
{
    /**
     *  Constructor
     */
    public function __construct( $context )
    {
        parent::__construct( $context );
    }

    /**
     *  switch to prepared context
     */
    public function prepareExecute()
    {
        return new Charcoal_PreparedContext( $this->getContext() );
    }


}

