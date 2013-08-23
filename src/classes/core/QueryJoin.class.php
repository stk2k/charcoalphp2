<?php
/**
* Query Target Element
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_QueryJoin extends Charcoal_Object
{
	var $_join_type;
	var $_model_name;
	var $_alias;
	var $_condition;

	/*
	 *  Constructor
	 */
	public function __construct( Charcoal_Integer $join_type )
	{
		$this->_join_type = ui($join_type);
	}

	/*
	 *  get join type
	 */
	public function getJoinType()
	{
		return $this->_join_type;
	}

	/*
	 *  get model name
	 */
	public function getModelName()
	{
		return $this->_model_name;
	}

	/*
	 *  set model name
	 */
	public function setModelName( Charcoal_String $model_name )
	{
		$this->_model_name = $model_name;
	}

	/*
	 *  get alias
	 */
	public function getAlias()
	{
		return $this->_alias;
	}

	/*
	 *  set alias
	 */
	public function setAlias( Charcoal_String $alias )
	{
		$this->_alias = $alias;
	}

	/*
	 *  get condition
	 */
	public function getCondition()
	{
		return $this->_condition;
	}

	/*
	 *  set condition
	 */
	public function setCondition( Charcoal_String $condition )
	{
		$this->_condition = $condition;
	}


}
return __FILE__;
