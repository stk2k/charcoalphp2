<?php
/**
* Query context for fluent interface
*
* PHP version 5
*
* @package    component.charcoal.db.context
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_QueryContext extends Charcoal_Object
{
	private $_gw;
	private $_query_target;
	private $_criteria;
	private $_fields;
	private $_resultset;

	/**
	 *  Constructor
	 */
	public function __construct( Charcoal_SmartGateway $gw )
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_SmartGateway', $gw );

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
	public function setQueryTarget( $query_target )
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
	public function setFields( $fields )
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

	/**
	 *  Set result set
	 */
	public function setResultSet( $resultset )
	{
		$this->_resultset = $resultset;
	}

	/**
	 *  Get criteria
	 */
	public function getResultSet()
	{
		return $this->_resultset;
	}

	/**
	 *  get count of records
	 */
	public function count()
	{
		return $this->_gw->count( $this->_query_target->toString(), $this->_criteria, $this->_fields );
	}

	/**
	 *  get max of records
	 */
	public function max()
	{
		return $this->_gw->max( $this->_query_target, $this->_criteria, $this->_fields );
	}

	/**
	 *  get min of records
	 */
	public function min()
	{
		return $this->_gw->min( $this->_query_target, $this->_criteria, $this->_fields );
	}

	/**
	 *  get sum of records
	 */
	public function sum()
	{
		return $this->_gw->sum( $this->_query_target, $this->_criteria, $this->_fields );
	}

	/**
	 *  get avg of records
	 */
	public function avg()
	{
		return $this->_gw->avg( $this->_query_target, $this->_criteria, $this->_fields );
	}

	/**
	 *  find first record
	 *
	 * @return Charcoal_ResultContext    result context
	 */
	public function findFirst()
	{
		$this->_resultset = $this->_gw->findFirst( $this->_query_target, $this->_criteria, $this->_fields );

		return new Charcoal_ResultContext( $this );
	}

	/**
	 *  find all records
	 *
	 * @return Charcoal_ResultContext    result context
	 */
	public function findAll()
	{
		$this->_resultset = $this->_gw->findAll( $this->_query_target, $this->_criteria, $this->_fields );

		return new Charcoal_ResultContext( $this );
	}

	/**
	 *  find all records for update
	 *
	 * @return Charcoal_ResultContext    result context
	 */
	public function findAllForUpdate()
	{
		$this->_resultset = $this->_gw->findAllForUpdate( $this->_query_target, $this->_criteria, $this->_fields );

		return new Charcoal_ResultContext( $this );
	}


}

