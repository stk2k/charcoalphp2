<?php
/**
* Rectangle
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Position extends Charcoal_Object
{
	private $_left;
	private $_top;

	/*
	 *	Construct
	 */
	public function __construct( Charcoal_Integer $left, Charcoal_Integer $top )
	{
		parent::__construct();

		$this->_left     = ui($left);
		$this->_top      = ui($top);
	}

	/*
	 *	Get Left
	 */
	public function getLeft()
	{
		return $this->_left;
	}

	/*
	 *	Set Left
	 */
	public function setLeft( Charcoal_Integer $left )
	{
		$this->_left = ui($left);
	}

	/*
	 *	Get Top
	 */
	public function getTop()
	{
		return $this->_top;
	}

	/*
	 *	Set Top
	 */
	public function setTop( Charcoal_Integer $top )
	{
		$this->_top = ui($top);
	}

}

