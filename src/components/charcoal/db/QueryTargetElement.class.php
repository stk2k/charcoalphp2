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
class Charcoal_QueryTargetElement extends Charcoal_Object
{
	var $_type;
	var $_string;

	/*
	 *  Constructor
	 */
	public function __construct( Charcoal_Integer $type, Charcoal_String $string = NULL )
	{
		$this->_type   = ui($type);
		$this->_string = us($string);
	}

	/*
	 *  query target type
	 */
	public function getType()
	{
		return $this->_type;
	}

	/*
	 *  string data
	 */
	public function getString()
	{
		return $this->_string;
	}
}

