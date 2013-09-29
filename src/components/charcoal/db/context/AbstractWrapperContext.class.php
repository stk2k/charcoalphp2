<?php
/**
* base class for wrppert context
*
* PHP version 5
*
* @package    components.charcoal.db.context
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_AbstractWrapperContext extends Charcoal_Object
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
	 *  get wrapped context
	 *
	 * @return Charcoal_QueryContext    wrapped context
	 */
	public function getContext()
	{
		return $this->_context;
	}

}

