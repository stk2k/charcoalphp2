<?php
/**
* Query context for fluent interface
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_QueryContext extends Charcoal_Object
{
	private $_gw;
	private $_query_target;
	private $_criteria;
	private $_fields;

	/**
	 *  Constructor
	 */
	public function __construct( Charcoal_SmartGateway $gw )
	{
		$this->_gw = $gw;
		$this->_criteria = new Charcoal_SQLCriteria();
	}

	/**
	 *  Get smart gateway
	 */
	public function getSmartGateway()
	{
		return $this->_gw;
	}

	/**
	 *  Get query target
	 */
	public function getQueryTarget()
	{
		return $this->_query_target;
	}

	/**
	 *  Set query target
	 */
	public function setQueryTarget( Charcoal_QueryTarget $query_target )
	{
		$this->_query_target = $query_target;
	}

	/**
	 *  Get fields
	 */
	public function getFields()
	{
		return $this->_fields;
	}

	/**
	 *  Set fields
	 */
	public function setFields( Charcoal_Vector $fields )
	{
		$this->_fields = $fields;
	}

	/**
	 *  Get criteria
	 */
	public function getCriteria()
	{
		return $this->_criteria;
	}


}
return __FILE__;
