<?php
/**
* SQL criteria class
*
* PHP version 5
*
* @package    components.charcoal.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_SQLCriteria extends Charcoal_Object
{
	private $where;
	private $params;
	private $order_by;
	private $limit;
	private $offset;
	private $group_by;

	/**
	 *  Constructor
	 *  
	 *  @param string $where        string data used after WHERE clause
	 *  @param array $params        array data which will be used for binding
	 *  @param string $order_by     string data used after ORDER BY clause
	 *  @param int $limit       integer data used after LIMIT clause
	 *  @param int $offset      integer data used after OFFSET clause
	 *  @param string $group_by     string data used after GROUP BY clause
	 */
	public function __construct( $where = NULL, $params = NULL, $order_by = NULL, $limit = NULL, $offset = NULL, $group_by = NULL )
	{
		Charcoal_ParamTrait::checkString( 1, $where, TRUE );
		Charcoal_ParamTrait::checkString( 2, $params, TRUE );
		Charcoal_ParamTrait::checkString( 3, $order_by, TRUE );
		Charcoal_ParamTrait::checkInteger( 4, $limit, TRUE );
		Charcoal_ParamTrait::checkInteger( 5, $offset, TRUE );
		Charcoal_ParamTrait::checkString( 6, $group_by, TRUE );

		$this->where     = us($where);
		$this->params    = uv($params);
		$this->order_by  = us($order_by);
		$this->limit     = ui($limit);
		$this->offset    = ui($offset);
		$this->group_by  = us($group_by);
	}

	/**
	 *  Get WHERE clause
	 *  
	 *  @return string       string data used after WHERE clause
	 */
	public function getWhere()
	{
		return $this->where;
	}

	/**
	 *  Set WHERE clause
	 *  
	 *  @param string $where        string data used after WHERE clause
	 */
	public function setWhere( $where )
	{
		Charcoal_ParamTrait::checkString( 1, $where );

		$this->where = us($where);
	}

	/**
	 *  Add WHERE clause
	 *  
	 *  @return string       string data used after WHERE clause
	 */
	public function addWhere( $where, $operator = NULL )
	{
		Charcoal_ParamTrait::checkString( 1, $where );
		Charcoal_ParamTrait::checkString( 2, $operator, TRUE );

		if ( $operator === NULL ){
			$operator = 'AND';
		}

		if ( $this->where && $this->where->isEmpty() ){
			$this->where = "({$this->where}) {$operator} ({$where})";
		}
		else{
			$this->where = us($where);
		}
	}

	/**
	 *  Get parameters
	 *  
	 *  @return array        array data which will be used for binding
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 *  Set parameters
	 *  
	 *  @param array $params        array data which will be used for binding
	 */
	public function setParams( $params )
	{
		Charcoal_ParamTrait::checkVector( 1, $params );

		$this->params = uv($params);
	}

	/**
	 *  Add multiple parameters
	 *  
	 *  @param array $params        array data to add
	 */
	public function addParams( $params )
	{
		Charcoal_ParamTrait::checkVector( 1, $params );

		if ( $this->params ){
			$this->params->addAll( $params );
		}
		else{
			$this->params = uv($params);
		}
	}

	/**
	 *  Get ORDER BY clause
	 *  
	 *  @return string        ORDER BY clause
	 */
	public function getOrderBy()
	{
		return $this->order_by;
	}

	/**
	 *  Set ORDER BY clause
	 *  
	 *  @param string $order_by        ORDER BY clause
	 */
	public function setOrderBy( $order_by )
	{
		Charcoal_ParamTrait::checkString( 1, $order_by );

		$this->order_by = us($order_by);
	}

	/**
	 *  Get LIMIT clause
	 *  
	 *  @return string        LIMIT clause
	 */
	public function getLimit()
	{
		return $this->limit;
	}

	/**
	 *  Set LIMIT clause
	 *  
	 *  @param int $limit        LIMIT clause
	 */
	public function setLimit( $limit )
	{
		Charcoal_ParamTrait::checkInteger( 1, $limit );

		$this->limit = ui($limit);
	}

	/**
	 *  Get OFFSET clause
	 *  
	 *  @return string        OFFSET clause
	 */
	public function getOffset()
	{
		return $this->offset;
	}

	/**
	 *  Set OFFSET clause
	 *  
	 *  @param int $limit        OFFSET clause
	 */
	public function setOffset( Charcoal_Integer $offset )
	{
		Charcoal_ParamTrait::checkInteger( 1, $offset );

		$this->offset = ui($offset);
	}

	/**
	 *  Get GROUP BY clause
	 *  
	 *  @return string        GROUP BY clause
	 */
	public function getGroupBy()
	{
		return $this->group_by;
	}

	/**
	 *  Set GROUP BY clause
	 *  
	 *  @param string $limit        GROUP BY clause
	 */
	public function setGroupBy( $group_by )
	{
		Charcoal_ParamTrait::checkString( 1, $group_by );

		$this->group_by = us($group_by);
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		$str  = "[SQLCriteria: ";
		$str .= "where=" . $this->where;
		$str .= "params=" . $this->params;
		$str .= "order_by=" . $this->order_by;
		$str .= "limit=" . $this->limit;
		$str .= "offset=" . $this->offset;
		$str .= "]";

		return $str;
	}
}

