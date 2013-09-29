<?php
/**
* Offset context for fluent interface
*
* PHP version 5
*
* @package    components.charcoal.db.context
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_OffsetContext extends Charcoal_AbstractWrapperContext
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
	 *  switch to group by context 
	 */
	public function groupBy( Charcoal_String $group_by )
	{
		$this->getContext()->getCriteria()->setGroupBy( $group_by );

		return new Charcoal_GroupByContext( $this->getContext() );
	}


}

