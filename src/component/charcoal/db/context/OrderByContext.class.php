<?php
/**
* Order by context for fluent interface
*
* PHP version 5
*
* @package    component.charcoal.db.context
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_OrderByContext extends Charcoal_AbstractWrapperContext
{
    /**
     *  Constructor
     *
     * @param Charcoal_QueryContext $context
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

    /**
     *  switch to limit context
     *
     *  @param integer|Charcoal_Integer $limit       integer data used after LIMIT clause
     *
     * @return Charcoal_LimitContext
     */
    public function limit( $limit )
    {
        $this->getContext()->getCriteria()->setLimit( $limit );

        return new Charcoal_LimitContext( $this->getContext() );
    }

    /**
     *  switch to offset context
     *
     * @param mixed $offset
     *
     * @return Charcoal_OffsetContext
     */
    public function offset( $offset )
    {
        $this->getContext()->getCriteria()->setOffset( $offset );

        return new Charcoal_OffsetContext( $this->getContext() );
    }

    /**
     *  switch to group by context
     *
     * @param string|Charcoal_String $group_by
     *
     * @return Charcoal_GroupByContext
     */
    public function groupBy( $group_by )
    {
        $this->getContext()->getCriteria()->setGroupBy( $group_by );

        return new Charcoal_GroupByContext( $this->getContext() );
    }


}

