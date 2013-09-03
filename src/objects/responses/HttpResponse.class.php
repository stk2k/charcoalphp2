<?php
/**
* HTTP Response
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_HttpResponse extends Charcoal_BaseResponse
{
	private $_status;
	private $_cookie;

	private $_headers;	// array of Charcoal_HttpHeader

	/*
	 *	Construct object
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_vars     = array();
		$this->_cookie   = new Charcoal_Cookie();
		$this->_headers  = array();
	}

	/*
	 *  add response header
	 *
	 * @param Charcoal_String $header   header string to send
	 * @param Charcoal_Boolean $replace   TRUE to replace same header
	 *
	 * @return Charcoal_String
	 */
	public function addHeader( Charcoal_String $header, Charcoal_Boolean $replace = NULL )
	{
		if ( $replace === NULL ){
			$replace = b(TRUE);
		}
		$this->_headers[] = new Charcoal_HttpHeader( $header, $replace );
	}

	/*
	 *  flush response header
	 */
	public function flushHeaders()
	{
		// add cookie headers
		$this->_cookie->writeAll();

		// output headers
		foreach( $this->_headers as $h ){
			header( $h->getHeader(), $h->getReplace() );
			log_debug( "system, debug, response", "header flushed: $h" );
		}

		// erase all headers
		$this->_headers = array();
	}

	/*
	 *  output HTTP header
	 *
	 * @param Charcoal_String $header   Header to output
	 * @param Charcoal_Boolean $flush_now   Flushes header immediately
	 */
	public function header( Charcoal_String $header, Charcoal_Boolean $flush_now = NULL )
	{

		if ( $flush_now == NULL ){
			$flush_now = b(TRUE);
		}

		$this->addHeader( s($header) );

		if ( $flush_now->isTrue() ){
			$this->flushHeaders();
		}
	}

	/*
	 *  HTTP redirect
	 *
	 * @param Charcoal_String $url   Redirect URL
	 * @param Charcoal_Boolean $flush_now   Flushes header immediately
	 */
	public function redirect( Charcoal_String $url, Charcoal_Boolean $flush_now = NULL )
	{
		if ( $flush_now == NULL ){
			$flush_now = b(TRUE);
		}

//		$this->header( s("HTTP/1.0 302 Found"), $flush_now );
		$this->header( s("Location: $url"), $flush_now );
	}

	/*
	 *  Get all cookie values as array
	 *
	 * @return array
	 */
	public function getCookies()
	{
		return $this->_cookie->toArray();
	}

	/*
	 *  Get cookie value
	 *
	 * @param Charcoal_String $name   cookie name to get
	 *
	 * @return Charcoal_String
	 */
	public function getCookie( Charcoal_String $name )
	{
		return $this->_cookie->getValue( $name );
	}

	/*
	 *  Set cookie value
	 *
	 * @param Charcoal_String $name   cookie name to set
	 * @param Charcoal_String $value   cookie value to set
	 *
	 * @return Charcoal_String
	 */
	public function setCookie( Charcoal_String $name, Charcoal_String $value )
	{
		$this->_cookie->setValue( $name, $value );
	}

	/*
	 *  Get status code
	 *
	 * @return int   HTTP status code
	 */
	public function getStatusCode()
	{
		return $this->_status;
	}

	/*
	 *  Set HTTP status code
	 *
	 * @return Charcoal_Integer $status_code   HTTP status code
	 */
	public function setStatusCode( Charcoal_Integer $status_code )
	{
		$this->_status = ui(status_code);
	}


}

