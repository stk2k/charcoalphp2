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
class Charcoal_OrderByContext extends Charcoal_Object
{
	private $_context;

	/**
	 *  Constructor
	 */
	public function __construct( Charcoal_QueryContext $context )
	{
		$this->_context = $context;
	}

	/**
	 *  switch to prepared context 
	 */
	public function prepare()
	{
		return new Charcoal_PreparedContext( $this->_context );
	}

	/**
	 *  switch to limit context 
	 */
	public function limit( Charcoal_Integer $limit )
	{
		$this->_context->getCriteria()->setLimit( $limit );

		return new Charcoal_LimitContext( $this->_context );
	}

	/**
	 *  switch to offset context 
	 */
	public function offset( Charcoal_Integer $offset )
	{
		$this->_context->getCriteria()->setOffset( $offset );

		return new Charcoal_OffsetContext( $this->_context );
	}

	/**
	 *  switch to group by context 
	 */
	public function groupBy( Charcoal_String $group_by )
	{
		$this->_context->getCriteria()->setGroupBy( $group_by );

		return new Charcoal_GroupByContext( $this->_context );
	}


}
return __FILE__;
