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
	private $where;
	private $params;
	private $order_by;
	private $limit;
	private $offset;
	private $group_by;

	/*
	 *    コンストラクタ
	 */
	public function __construct( Charcoal_String $where = NULL, Charcoal_Vector $params = NULL, Charcoal_String $order_by = NULL, Charcoal_Integer $limit = NULL, Charcoal_Integer $offset = NULL, Charcoal_String $group_by = NULL )
	{
		$this->where     = $where ? $where->trim() : NULL;
		$this->params    = $params;
		$this->order_by  = $order_by ? $order_by->trim() : NULL;
		$this->limit     = $limit;
		$this->offset    = $offset;
		$this->group_by  = $group_by ? $group_by->trim() : NULL;
	}

	/*
	 * WHERE句を取得
	 */
	public function getWhere()
	{
		return $this->where;
	}

	/*
	 * WHERE句を設定
	 */
	public function setWhere( Charcoal_String $where )
	{
		$this->where = $where;
	}

	/**
	 * add WHERE clause
	 */
	public function addWhere( Charcoal_String $where, Charcoal_String $operator = NULL )
	{
		if ( $operator === NULL ){
			$operator = s('AND');
		}

		if ( $this->where && !$this->where->isEmpty() ){
			$this->where = "({$this->where}) {$operator} ({$where})";
		}
		else{
			$this->where = $where;
		}
	}

	/*
	 * パラメータを取得
	 */
	public function getParams()
	{
		return $this->params;
	}

	/*
	 * パラメータを設定
	 */
	public function setParams( Charcoal_Vector $params )
	{
		$this->params = $params;
	}

	/*
	 * Add parameters
	 */
	public function addParams( Charcoal_Vector $params )
	{
		if ( $this->params ){
			$this->params->addAll( $params );
		}
		else{
			$this->params = $params;
		}
	}

	/*
	 * ORDER BY句を取得
	 */
	public function getOrderBy()
	{
		return $this->order_by;
	}

	/*
	 * ORDER BY句を設定
	 */
	public function setOrderBy( Charcoal_String $order_by )
	{
		$this->order_by = $order_by;
	}

	/*
	 * LIMIT句を取得
	 */
	public function getLimit()
	{
		return $this->limit;
	}

	/*
	 * LIMIT句を設定
	 */
	public function setLimit( Charcoal_Integer $limit )
	{
		$this->limit = $limit;
	}

	/*
	 * OFFSET句を取得
	 */
	public function getOffset()
	{
		return $this->offset;
	}

	/*
	 * OFFSET句を設定
	 */
	public function setOffset( Charcoal_Integer $offset )
	{
		$this->offset = $offset;
	}

	/*
	 * GROUP BY句を取得
	 */
	public function getGroupBy()
	{
		return $this->group_by;
	}

	/*
	 * GROUP BY句を設定
	 */
	public function setGroupBy( Charcoal_String $group_by )
	{
		$this->group_by = $group_by;
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		$str  = "[SQLCondition: ";
		$str .= "where=" . $this->where;
		$str .= "params=" . $this->params;
		$str .= "order_by=" . $this->order_by;
		$str .= "limit=" . $this->limit;
		$str .= "offset=" . $this->offset;
		$str .= "]";

		return $str;
	}
}

