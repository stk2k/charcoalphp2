<?php
/**
* Rectangle
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_Rectangle extends Charcoal_Object
{
	private $_left;
	private $_top;
	private $_width;
	private $_height;

	/*
	 *	Construct
	 */
	public function __construct( Charcoal_Integer $left, Charcoal_Integer $top, Charcoal_Integer $width, Charcoal_Integer $height )
	{
		parent::__construct();

		$this->_left     = ui($left);
		$this->_top      = ui($top);
		$this->_width    = ui($width);
		$this->_height   = ui($height);
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

	/*
	 *	Get Width
	 */
	public function getWidth()
	{
		return $this->_width;
	}

	/*
	 *	Set Top
	 */
	public function setWidth( Charcoal_Integer $width )
	{
		$this->_width = ui($width);
	}

	/*
	 *	Get Height
	 */
	public function getHeight()
	{
		return $this->_height;
	}

	/*
	 *	Set Height
	 */
	public function setHeight( Charcoal_Integer $height )
	{
		$this->_height = ui($height);
	}




}

