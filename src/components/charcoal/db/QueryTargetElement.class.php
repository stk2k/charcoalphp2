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
	private $type;
	private $string;

	/*
	 *  Constructor
	 */
	public function __construct( Charcoal_Integer $type, Charcoalstring $string = NULL )
	{
		$this->type   = ui($type);
		$this->string = us($string);
	}

	/*
	 *  query target type
	 */
	public function getType()
	{
		return $this->type;
	}

	/*
	 *  string data
	 */
	public function getString()
	{
		return $this->string;
	}
}

