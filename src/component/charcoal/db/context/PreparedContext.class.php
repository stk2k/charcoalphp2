<?php
/**
* Prepared context for fluent interface
*
* PHP version 5
*
* @package    component.charcoal.db.context
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
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
	public function bind( $params )
	{
		$this->getContext()->getCriteria()->addParams( $params );

		return new Charcoal_BindedContext( $this->getContext() );
	}

	/**
	 *  get count of records
	 */
	public function count( $comment = '' )
	{
		return $this->getContext()->count( $comment );
	}

	/**
	 *  get max of records
	 */
	public function max( $comment = '' )
	{
		return $this->getContext()->max( $comment );
	}

	/**
	 *  get min of records
	 */
	public function min( $comment = '' )
	{
		return $this->getContext()->min( $comment );
	}

	/**
	 *  get sum of records
	 */
	public function sum( $comment = '' )
	{
		return $this->getContext()->sum( $comment );
	}

	/**
	 *  get avg of records
	 */
	public function avg( $comment = '' )
	{
		return $this->getContext()->avg( $comment );
	}

	/**
	 *  find first record
	 *
	 * @return Charcoal_ResultContext    result context
	 */
	public function findFirst( $comment = '' )
	{
		return $this->getContext()->findFirst( $comment );
	}

	/**
	 *  find all records
	 *
	 * @return Charcoal_ResultContext    result context
	 */
	public function findAll( $comment = '' )
	{
		return $this->getContext()->findAll( $comment );
	}

	/**
	 *  find all records for update
	 *
	 * @return Charcoal_ResultContext    result context
	 */
	public function findAllForUpdate( $comment = '' )
	{
		return $this->getContext()->findAllForUpdate( $comment );
	}


}

