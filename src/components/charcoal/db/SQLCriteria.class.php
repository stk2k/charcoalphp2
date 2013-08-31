<?php
/**
* SQL条件クラス
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_SQLCriteria extends Charcoal_Object
{
	var $_where;
	var $_params;
	var $_order_by;
	var $_limit;
	var $_offset;
	var $_group_by;

	/*
	 *    コンストラクタ
	 */
	public function __construct( Charcoal_String $where = NULL, Charcoal_Vector $params = NULL, Charcoal_String $order_by = NULL, Charcoal_Integer $limit = NULL, Charcoal_Integer $offset = NULL, Charcoal_String $group_by = NULL )
	{
		$this->_where     = $where ? $where->trim() : NULL;
		$this->_params    = $params;
		$this->_order_by  = $order_by ? $order_by->trim() : NULL;
		$this->_limit     = $limit;
		$this->_offset    = $offset;
		$this->_group_by  = $group_by ? $group_by->trim() : NULL;
	}

	/*
	 * WHERE句を取得
	 */
	public function getWhere()
	{
		return $this->_where;
	}

	/*
	 * WHERE句を設定
	 */
	public function setWhere( Charcoal_String $where )
	{
		$this->_where = $where;
	}

	/**
	 * add WHERE clause
	 */
	public function addWhere( Charcoal_String $where, Charcoal_String $operator = NULL )
	{
		if ( $operator === NULL ){
			$operator = s('AND');
		}

		if ( $this->_where && !$this->_where->isEmpty() ){
			$this->_where = "({$this->_where}) {$operator} ({$where})";
		}
		else{
			$this->_where = $where;
		}
	}

	/*
	 * パラメータを取得
	 */
	public function getParams()
	{
		return $this->_params;
	}

	/*
	 * パラメータを設定
	 */
	public function setParams( Charcoal_Vector $params )
	{
		$this->_params = $params;
	}

	/*
	 * Add parameters
	 */
	public function addParams( Charcoal_Vector $params )
	{
		if ( $this->_params ){
			$this->_params->addAll( $params );
		}
		else{
			$this->_params = $params;
		}
	}

	/*
	 * ORDER BY句を取得
	 */
	public function getOrderBy()
	{
		return $this->_order_by;
	}

	/*
	 * ORDER BY句を設定
	 */
	public function setOrderBy( Charcoal_String $order_by )
	{
		$this->_order_by = $order_by;
	}

	/*
	 * LIMIT句を取得
	 */
	public function getLimit()
	{
		return $this->_limit;
	}

	/*
	 * LIMIT句を設定
	 */
	public function setLimit( Charcoal_Integer $limit )
	{
		$this->_limit = $limit;
	}

	/*
	 * OFFSET句を取得
	 */
	public function getOffset()
	{
		return $this->_offset;
	}

	/*
	 * OFFSET句を設定
	 */
	public function setOffset( Charcoal_Integer $offset )
	{
		$this->_offset = $offset;
	}

	/*
	 * GROUP BY句を取得
	 */
	public function getGroupBy()
	{
		return $this->_group_by;
	}

	/*
	 * GROUP BY句を設定
	 */
	public function setGroupBy( Charcoal_String $group_by )
	{
		$this->_group_by = $group_by;
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		$str  = "[SQLCondition: ";
		$str .= "where=" . $this->_where;
		$str .= "params=" . $this->_params;
		$str .= "order_by=" . $this->_order_by;
		$str .= "limit=" . $this->_limit;
		$str .= "offset=" . $this->_offset;
		$str .= "]";

		return $str;
	}
}

