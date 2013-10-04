<?php
/**
* Limit context for fluent interface
*
* PHP version 5
*
* @package    component.charcoal.db.context
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_LimitContext extends Charcoal_AbstractWrapperContext
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
	public function prepare()
	{
		return new Charcoal_PreparedContext( $this->getContext() );
	}

	/**
	 *  switch to offset context 
	 */
	public function offset( Charcoal_Integer $offset )
	{
		$this->getContext()->getCriteria()->setOffset( $offset );

		return new Charcoal_OffsetContext( $this->getContext() );
	}

	/**
	 *  switch to group by context 
	 */
	public function groupBy( Charcoal_String $group_by )
	{
		$this->getContext()->getCriteria()->setGroupBy( $group_by );

		return new Charcoal_GroupByContext( $this->getContext() );
	}


}

