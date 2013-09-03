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
	private $_resultset;

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

	/**
	 *  Set result set
	 */
	public function setResultSet( Charcoal_Vector $resultset )
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
		if ( $this->_fields ){
			return $this->_gw->count( $this->_query_target->toString(), $this->_criteria, $this->_fields );
		}
		else{
			return $this->_gw->count( $this->_query_target->toString(), $this->_criteria );
		}
	}

	/**
	 *  get max of records
	 */
	public function max()
	{
		if ( $this->_fields ){
			return $this->_gw->max( $this->_query_target, $this->_criteria, $this->_fields );
		}
		else{
			return $this->_gw->max( $this->_query_target, $this->_criteria );
		}
	}

	/**
	 *  get min of records
	 */
	public function min()
	{
		if ( $this->_fields ){
			return $this->_gw->min( $this->_query_target, $this->_criteria, $this->_fields );
		}
		else{
			return $this->_gw->min( $this->_query_target, $this->_criteria );
		}
	}

	/**
	 *  get sum of records
	 */
	public function sum()
	{
		if ( $this->_fields ){
			return $this->_gw->sum( $this->_query_target, $this->_criteria, $this->_fields );
		}
		else{
			return $this->_gw->sum( $this->_query_target, $this->_criteria );
		}
	}

	/**
	 *  get avg of records
	 */
	public function avg()
	{
		if ( $this->_fields ){
			return $this->_gw->avg( $this->_query_target, $this->_criteria, $this->_fields );
		}
		else{
			return $this->_gw->avg( $this->_query_target, $this->_criteria );
		}
	}

	/**
	 *  find first record
	 *
	 * @return Charcoal_ResultContext    result context
	 */
	public function findFirst()
	{
		if ( $fields ){
			$this->_resultset = $this->_gw->findFirst( $this->_query_target, $this->_criteria, $this->_fields );
		}
		else{
			$this->_resultset = $this->_gw->findFirst( $this->_query_target, $this->_criteria );
		}

		return new Charcoal_ResultContext( $this );
	}

	/**
	 *  find all records
	 *
	 * @return Charcoal_ResultContext    result context
	 */
	public function findAll()
	{
		if ( $fields ){
			$this->_resultset = $this->_gw->findAll( $this->_query_target, $this->_criteria, $this->_fields );
		}
		else{
			$this->_resultset = $this->_gw->findAll( $this->_query_target, $this->_criteria );
		}

		return new Charcoal_ResultContext( $this );
	}

	/**
	 *  find all records for update
	 *
	 * @return Charcoal_ResultContext    result context
	 */
	public function findAllForUpdate()
	{
		if ( $fields ){
			$this->_resultset = $this->_gw->findAllForUpdate( $this->_query_target, $this->_criteria, $this->_fields );
		}
		else{
			$this->_resultset = $this->_gw->findAllForUpdate( $this->_query_target, $this->_criteria );
		}

		return new Charcoal_ResultContext( $this );
	}


}

