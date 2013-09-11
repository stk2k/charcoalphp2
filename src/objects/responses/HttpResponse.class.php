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
	private $status;
	private $cookie;

	private $headers;	// array of Charcoal_HttpHeader

	/*
	 *	Construct object
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_vars     = array();
		$this->headers  = array();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		// use cookie
		$usecookie  = $this->getSandbox()->getProfile()->getBoolean( 'USE_COOKIE', FALSE );
		$this->cookie = $usecookie ? new Charcoal_CookieWriter() : NULL;
	}

	/**
	 * destruct instance
	 */
	public function terminate()
	{
		parent::terminate();

		$this->flushHeaders();
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
		$this->headers[] = new Charcoal_HttpHeader( $header, $replace );
	}

	/*
	 *  flush response header
	 */
	public function flushHeaders()
	{
		// add cookie headers
		if ( $this->cookie ){
			$this->cookie->writeAll();
		}

		// output headers
		foreach( $this->headers as $h ){
			header( $h->getHeader(), $h->getReplace() );
			log_debug( "system, debug, response", "header flushed: $h" );
		}

		// erase all headers
		$this->headers = array();
	}

	/*
	 *  clear response header
	 */
	public function clearHeaders()
	{
		header_remove();

		// erase all headers
		$this->headers = array();
	}

	/*
	 *  output HTTP header
	 *
	 * @param Charcoal_String $header            header to output
	 * @param Charcoal_Boolean $flush_now        flushes header immediately
	 */
	public function header( Charcoal_String $header, Charcoal_Boolean $flush_now = NULL )
	{
		$this->addHeader( s($header) );

		if ( !$flush_now || $flush_now->isTrue() ){
			$this->flushHeaders();
		}
	}

	/*
	 *  HTTP redirect
	 *
	 * @param Charcoal_URL $url   Redirect URL
	 * @param Charcoal_Boolean $flush_now   Flushes header immediately
	 */
	public function redirect_( Charcoal_URL $url, Charcoal_Boolean $flush_now = NULL )
	{
		if ( $flush_now == NULL ){
			$flush_now = b(TRUE);
		}

//		$this->header( s("HTTP/1.0 302 Found"), $flush_now );
		$this->clearHeaders();
		$this->header( s("Location: $url"), $flush_now );
	}

	/*
	 *  HTTP redirect(weak API)
	 *
	 * @param Charcoal_URL $url   Redirect URL
	 * @param Charcoal_Boolean $flush_now   Flushes header immediately
	 */
	public function redirect( $url, $flush_now = FALSE )
	{
		$url = new Charcoal_URL( s($url) );
		$this->redirect_( $url, b($flush_now) );
	}

	/*
	 *  Get all cookie values as array
	 *
	 * @return array
	 */
	public function getCookies()
	{
		return $this->cookie ? $this->cookie->toArray() : NULL;
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
		return $this->cookie ? $this->cookie->getValue( $name ) : NULL;
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
		if ( $this->cookie ){
			$this->cookie->setValue( $name, $value );
		}
	}

	/*
	 *  Get status code
	 *
	 * @return int   HTTP status code
	 */
	public function getStatusCode()
	{
		return $this->status;
	}

	/*
	 *  Set HTTP status code
	 *
	 * @return Charcoal_Integer $status_code   HTTP status code
	 */
	public function setStatusCode( Charcoal_Integer $status_code )
	{
		$this->status = ui(status_code);
	}


}

