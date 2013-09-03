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

	/**
	 *  get count of records
	 */
	public function count()
	{
		return $this->_context->count();
	}

	/**
	 *  get max of records
	 */
	public function max()
	{
		return $this->_context->max();
	}

	/**
	 *  get min of records
	 */
	public function min()
	{
		return $this->_context->min();
	}

	/**
	 *  get sum of records
	 */
	public function sum()
	{
		return $this->_context->sum();
	}

	/**
	 *  get avg of records
	 */
	public function avg()
	{
		return $this->_context->avg();
	}

	/**
	 *  find first record
	 *
	 * @return Charcoal_ResultContext    result context
	 */
	public function findFirst()
	{
		return $this->_context->findFirst();
	}

	/**
	 *  find all records
	 *
	 * @return Charcoal_ResultContext    result context
	 */
	public function findAll()
	{
		return $this->_context->findAll();
	}

	/**
	 *  find all records for update
	 *
	 * @return Charcoal_ResultContext    result context
	 */
	public function findAllForUpdate()
	{
		return $this->_context->findAllForUpdate();
	}


}

