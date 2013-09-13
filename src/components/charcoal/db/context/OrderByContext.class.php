<?php
/**
* Order by context for fluent interface
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_OrderByContext extends Charcoal_AbstractWrapperContext
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
	 *  switch to limit context 
	 */
	public function limit( Charcoal_Integer $limit )
	{
		$this->getContext()->getCriteria()->setLimit( $limit );

		return new Charcoal_LimitContext( $this->getContext() );
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

