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
		$gw           = $this->_context->getSmartGateway();
		$query_target = $this->_context->getQueryTarget();
		$criteria     = $this->_context->getCriteria();
		$fields       = $this->_context->getFields();

		if ( $fields ){
			return $gw->count( $query_target, $criteria, $fields );
		}
		else{
			return $gw->count( $query_target, $criteria );
		}
	}

	/**
	 *  get max of records
	 */
	public function max()
	{
		$gw           = $this->_context->getSmartGateway();
		$query_target = $this->_context->getQueryTarget();
		$criteria     = $this->_context->getCriteria();
		$fields       = $this->_context->getFields();

		if ( $fields ){
			return $gw->max( $query_target, $criteria, $fields );
		}
		else{
			return $gw->max( $query_target, $criteria );
		}
	}

	/**
	 *  get min of records
	 */
	public function min()
	{
		$gw           = $this->_context->getSmartGateway();
		$query_target = $this->_context->getQueryTarget();
		$criteria     = $this->_context->getCriteria();
		$fields       = $this->_context->getFields();

		if ( $fields ){
			return $gw->min( $query_target, $criteria, $fields );
		}
		else{
			return $gw->min( $query_target, $criteria );
		}
	}

	/**
	 *  get sum of records
	 */
	public function sum()
	{
		$gw           = $this->_context->getSmartGateway();
		$query_target = $this->_context->getQueryTarget();
		$criteria     = $this->_context->getCriteria();
		$fields       = $this->_context->getFields();

		if ( $fields ){
			return $gw->sum( $query_target, $criteria, $fields );
		}
		else{
			return $gw->sum( $query_target, $criteria );
		}
	}

	/**
	 *  get avg of records
	 */
	public function avg()
	{
		$gw           = $this->_context->getSmartGateway();
		$query_target = $this->_context->getQueryTarget();
		$criteria     = $this->_context->getCriteria();
		$fields       = $this->_context->getFields();

		if ( $fields ){
			return $gw->avg( $query_target, $criteria, $fields );
		}
		else{
			return $gw->avg( $query_target, $criteria );
		}
	}

	/**
	 *  find first record
	 *
	 * @return Charcoal_ResultContext    result context
	 */
	public function findFirst( Charcoal_Integer $limit = NULL, Charcoal_Integer $offset = NULL )
	{
		$gw           = $this->_context->getSmartGateway();
		$query_target = $this->_context->getQueryTarget();
		$criteria     = $this->_context->getCriteria();
		$fields       = $this->_context->getFields();

		if ( $offset ){
			$criteria->setOffset( $offset );
		}
		else if ( $limit ){
			$criteria->setLimit( $limit );
		}

		if ( $fields ){
			$rs = $this->_gw->findFirst( $query_target, $criteria, $fields );
		}
		else{
			$rs = $this->_gw->findFirst( $query_target, $criteria );
		}

		$this->_context->setResultSet( v($rs) );

		return new Charcoal_ResultContext( $this->_context );
	}

	/**
	 *  find all records
	 *
	 * @return Charcoal_ResultContext    result context
	 */
	public function findAll()
	{
		$gw           = $this->_context->getSmartGateway();
		$query_target = $this->_context->getQueryTarget();
		$criteria     = $this->_context->getCriteria();
		$fields       = $this->_context->getFields();

		if ( $fields ){
			$rs = $gw->findAll( $query_target, $criteria, $fields );
		}
		else{
			$rs = $gw->findAll( $query_target, $criteria );
		}

		$this->_context->setResultSet( v($rs) );

		return new Charcoal_ResultContext( $this->_context );
	}

	/**
	 *  find all records for update
	 *
	 * @return Charcoal_ResultContext    result context
	 */
	public function findAllForUpdate()
	{
		$gw           = $this->_context->getSmartGateway();
		$query_target = $this->_context->getQueryTarget();
		$criteria     = $this->_context->getCriteria();
		$fields       = $this->_context->getFields();

		if ( $fields ){
			$rs = $gw->findAllForUpdate( $query_target, $criteria, $fields );
		}
		else{
			$rs = $gw->findAllForUpdate( $query_target, $criteria );
		}

		$this->_context->setResultSet( v($rs) );

		return new Charcoal_ResultContext( $this->_context );
	}


}
return __FILE__;
