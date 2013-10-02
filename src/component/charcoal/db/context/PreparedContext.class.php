<?php
/**
* Prepared context for fluent interface
*
* PHP version 5
*
* @package    component.charcoal.db.context
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_PreparedContext extends Charcoal_AbstractWrapperContext
{
	/**
	 *  Constructor
	 */
	public function __construct( $context )
	{
		parent::__construct( $context );
	}

	/**
	 *  switch to binded context 
	 *
	 * @return Charcoal_BindedContext    binded context
	 */
	public function bind( Charcoal_Vector $params )
	{
		$this->getContext()->getCriteria()->addParams( $params );

		return new Charcoal_BindedContext( $this->getContext() );
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

