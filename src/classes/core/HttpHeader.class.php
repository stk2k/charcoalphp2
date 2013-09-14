<?php
/**
* HTTP Header
*
* PHP version 5
*
* @package    core
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
	public function __construct( Charcoal_String $header, Charcoal_Boolean $replace )
	{
		parent::__construct();

		$this->_header  = us($header);
		$this->_replace = ub($replace);
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
	public function setHeader( Charcoal_String $header )
	{
		$this->_header = us($header);
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
	public function setReplace( Charcoal_Boolean $replace )
	{
		$this->_replace = ub($replace);
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

