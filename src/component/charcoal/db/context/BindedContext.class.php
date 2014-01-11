<?php
/**
* Binded context for fluent interface
*
* PHP version 5
*
* @package    component.charcoal.db.context
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_BindedContext extends Charcoal_AbstractWrapperContext
{
	/**
	 *  Constructor
	 */
	public function __construct( $context )
	{
		parent::__construct( $context );
	}

	/**
	 *  bind a param
	 */
	public function add( Charcoal_String $field, Charcoal_Scalar $value )
	{
		$params = array( us($field) => $value->unbox() );
		$this->getContext()->addParams( v($params) );
		return $this;
	}

	/**
	 *  bind params
	 */
	public function addAll( Charcoal_Vector $params )
	{
		$this->getContext()->addParams( $params );
		return $this;
	}

	/**
	 *  get count of records
	 */
	public function count()
	{
		return $this->getContext()->count();
	}

	/**
	 *  get max of records
	 */
	public function max()
	{
		return $this->getContext()->max();
	}

	/**
	 *  get min of records
	 */
	public function min()
	{
		return $this->getContext()->min();
	}

	/**
	 *  get sum of records
	 */
	public function sum()
	{
		return $this->getContext()->sum();
	}

	/**
	 *  get avg of records
	 */
	public function avg()
	{
		return $this->getContext()->avg();
	}

	/**
	 *  find first record
	 *
	 * @return Charcoal_ResultContext    result context
	 */
	public function findFirst()
	{
		return $this->getContext()->findFirst();
	}

	/**
	 *  find all records
	 *
	 * @return Charcoal_ResultContext    result context
	 */
	public function findAll()
	{
		return $this->getContext()->findAll();
	}

	/**
	 *  find all records for update
	 *
	 * @return Charcoal_ResultContext    result context
	 */
	public function findAllForUpdate()
	{
		return $this->getContext()->findAllForUpdate();
	}


}

