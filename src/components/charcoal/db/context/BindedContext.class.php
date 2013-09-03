<?php
/**
* Binded context for fluent interface
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_BindedContext extends Charcoal_Object
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
	 *  bind a param
	 */
	public function add( Charcoal_String $field, Charcoal_Primitive $value )
	{
		$params = array( us($field) => $value->unbox() );
		$this->_context->addParams( v($params) );
		return $this;
	}

	/**
	 *  bind params
	 */
	public function addAll( Charcoal_Vector $params )
	{
		$this->_context->addParams( $params );
		return $this;
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

