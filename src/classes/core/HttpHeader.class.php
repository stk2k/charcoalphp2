<?php
/**
* HTTP Header
*
* PHP version 5
*
* @package    classes.core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_HttpHeader extends Charcoal_Object
{
	private $_header;
	private $_replace;

	/*
	 *	Construct object
	 */
	public function __construct( $header, $replace )
	{
		parent::__construct();

		$this->_header  = $header;
		$this->_replace = $replace;
	}

	/*
	 *  Get header
	 *
	 * @return string
	 */
	public function getHeader()
	{
		return $this->_header;
	}

	/*
	 *  Set header
	 *
	 * @param string
	 */
	public function setHeader( $header )
	{
		$this->_header = $header;
	}

	/*
	 *  Get replace flag
	 *
	 * @return boolean
	 */
	public function getReplace()
	{
		return $this->_replace;
	}

	/*
	 *  Set replace flag
	 *
	 * @param string
	 */
	public function setReplace( $replace )
	{
		$this->_replace = $replace;
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return $this->_header;
	}
}

