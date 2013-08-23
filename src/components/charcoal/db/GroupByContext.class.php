<?php
/**
* Group by context for fluent interface
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_GroupByContext extends Charcoal_Object
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


}
return __FILE__;
