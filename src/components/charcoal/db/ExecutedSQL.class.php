<?php
/**
* Executed SQL Log
*
* PHP version 5
*
* @package    components.charcoal.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_ExecutedSQL extends Charcoal_Object
{
	private $sql;
	private $params;

	/**
	 *  Constructor
	 *  
	 *  @param string $sql        SQL
	 *  @param array $params      array data which aree used for binding
	 */
	public function __construct( $sql, $params = NULL )
	{
		Charcoal_ParamTrait::checkString( 1, $sql );
		Charcoal_ParamTrait::checkString( 2, $params, TRUE );

		parent::__construct();

		$this->sql         = s($sql);
		$this->params      = v($params);
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
		return us($this->binded_sql);
	}
}

