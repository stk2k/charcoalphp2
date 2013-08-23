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

class Charcoal_RectangleFloat extends Charcoal_Object
{
	private $_left;
	private $_top;
	private $_width;
	private $_height;

	/*
	 *	Construct
	 */
	public function __construct( Charcoal_Float $left, Charcoal_Float $top, Charcoal_Float $width, Charcoal_Float $height )
	{
		parent::__construct();

		$this->_left     = uf($left);
		$this->_top      = uf($top);
		$this->_width    = uf($width);
		$this->_height   = uf($height);
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
	public function setLeft( Charcoal_Float $left )
	{
		$this->_left = uf($left);
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
	public function setTop( Charcoal_Float $top )
	{
		$this->_top = uf($top);
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
	public function setWidth( Charcoal_Float $width )
	{
		$this->_width = uf($width);
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
	public function setHeight( Charcoal_Float $height )
	{
		$this->_height = uf($height);
	}




}
return __FILE__;
