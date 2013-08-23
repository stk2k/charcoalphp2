<?php
/**
* Prepared context for fluent interface
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_PreparedContext extends Charcoal_Object
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
	 *  switch to binded context 
	 *
	 * @return Charcoal_BindedContext    binded context
	 */
	public function bind( Charcoal_Vector $params )
	{
		$this->_context->getCriteria()->addParams( $params );

		return new Charcoal_BindedContext( $this->_context );
	}


}
return __FILE__;
