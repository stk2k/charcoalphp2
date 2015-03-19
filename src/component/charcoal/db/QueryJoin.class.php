<?php
/**
* Query Target Element
*
* PHP version 5
*
* @package    component.charcoal.db
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_QueryJoin extends Charcoal_Object
{
	private $join_type;
	private $model_name;
	private $alias;
	private $condition;

	/*
	 *  Constructor
	 */
	public function __construct( $join_type )
	{
		$this->join_type = ui($join_type);
	}

	/*
	 *  get join type
	 */
	public function getJoinType()
	{
		return $this->join_type;
	}

	/*
	 *  get model name
	 */
	public function getModelName()
	{
		return $this->model_name;
	}

	/*
	 *  set model name
	 */
	public function setModelName( $model_name )
	{
		$this->model_name = $model_name;
	}

	/*
	 *  get alias
	 */
	public function getAlias()
	{
		return $this->alias;
	}

	/*
	 *  set alias
	 */
	public function setAlias( $alias )
	{
		$this->alias = $alias;
	}

	/*
	 *  get condition
	 */
	public function getCondition()
	{
		return $this->condition;
	}

	/*
	 *  set condition
	 */
	public function setCondition($condition )
	{
		$this->condition = $condition;
	}


}

