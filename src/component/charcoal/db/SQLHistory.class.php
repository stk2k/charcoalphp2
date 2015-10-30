<?php
/**
* Executed SQL Log
*
* PHP version 5
*
* @package    component.charcoal.db
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_SQLHistory extends Charcoal_Object
{
	private $sql;
	private $params;

	/**
	 *  Constructor
	 *  
	 * @param string|Charcoal_String $sql              SQL statement(placeholders can be included)
	 * @param array|Charcoal_HashMap $params           Parameter values for prepared statement
	 */
	public function __construct( $sql, $params = NULL )
	{
		parent::__construct();

		$this->sql         = $sql;
		$this->params      = $params;
	}

	/**
	 *  Get SQL
	 *  
	 *  @return string       Executed SQL
	 */
	public function getSQL()
	{
		return $this->sql;
	}

	/**
	 *  Get parameters
	 *  
	 *  @return array        array data which are used for binding
	 */
	public function getParams()
	{
		return $this->params;
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return '[sql]' . $this->sql . ' [params]' . implode( ',', $this->params );
	}
}

