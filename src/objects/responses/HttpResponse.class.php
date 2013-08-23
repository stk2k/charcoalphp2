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

class Charcoal_HttpResponse extends Charcoal_CharcoalObject implements Charcoal_IResponse
{
	private $_vars;
	private $_status;
	private $_filters;
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

	/*
	 * Add response filter
	 *
	 * @param Charcoal_IResponseFilter $filter filter to add
	 */
	public function addResponseFilter( Charcoal_IResponseFilter $filter )
	{
		$this->_filters[] = $filter;
	}

	/*
	 * Remove response filter
	 *
	 * @param Charcoal_IResponseFilter $filter filter to remove
	 */
	public function removeResponseFilter( Charcoal_IResponseFilter $filter )
	{
		if ( $this->_filters && is_array($this->_filters) ){
			foreach( $this->_filters as $key => $f ){
				if ( $f->equals($filter) ){
					unnset( $this->_filters[$key] );
					return;
				}
			}
		}
	}

	/*
	 *  Get all keys included in this container
	 *
	 * @return array
	 */
	public function getKeys() 
	{
		return array_keys($this->_vars);
	}

	/*
	 *  Get value from container if specified key is included, otherwise returns NULL.
	 *
	 * @param Charcoal_String $key   Key of the value 
	 *
	 * @return mixed
	 */
	public function get( Charcoal_String $key )
	{
		$key = us($key);
		return isset($this->_vars[$key]) ? $this->_vars[$key] : NULL;
	}

	/*
	 *  Get all values from container.
	 *
	 * @return array
	 */
	public function getAll()
	{
		return $this->_vars;
	}

	/*
	 *  Set value in container.
	 *
	 * @param Charcoal_String $key   Key of the value 
	 * @param mixed $value   Value to set
	 * @param Charcoal_Boolean $skip_filters   If TRUE, skip all registered filters
	 *
	 * @return mixed
	 */
	public function set( Charcoal_String $key, $value, Charcoal_Boolean $skip_filters = NULL )
	{
		if ( $skip_filters === NULL ){
			$skip_filters = b(FALSE);
		}
		if ( !$skip_filters->isTrue() ){
			$value = $this->_applyAllFilters($value);
		}
		$this->_vars[us($key)] = $value;
	}

	/*
	 *	Merge all elements of an array into container
	 *
	 * @param array $data   Array data to merge
	 */
	public function setArray( array $data )
	{
		$this->_vars = array_merge( $this->_vars, $data );
	}

	/*
	 *	Set all elements in a Properties object into container. All of container elements will be overwrited.
	 *
	 * @param Charcoal_Properties $data   Properties object to set
	 */
	public function setProperties( Charcoal_Properties $data )
	{
		$this->_vars = array_merge( $this->_vars, $data->getAll() );
	}

	/*
	 *	Merge all elements in a Properties object into container
	 *
	 * @param Charcoal_Properties $data   Properties object to merge
	 * @param Charcoal_Boolean $overwrite   If TRUE, container values will be overwrited by properties data.
	 */
	public function mergeProperties( Charcoal_Properties $data, Charcoal_Boolean $overwrite = NULL )
	{
		$overwrite = $overwrite ? $overwrite->isTrue() : FALSE;

		foreach( $data as $key => $value ){
			if ( !isset($this->_vars[$key]) || $overwrite ){
				$this->_vars[$key] = $value;
			}
		}
	}

	/*
	 * Copy parameters from request object. All of container elements will be overwrited.
	 *
	 * @param Charcoal_IRequest $data   Request object to set
	 */
	public function setFromRequest( Charcoal_IRequest $data )
	{
		$this->_vars = array_merge( $this->_vars, $data->getAll() );
	}

	/*
	 *  apply all filters
	 *
	 * @param mixed $value   Value to apply
	 */
	private function _applyAllFilters( $value )
	{
		// フィルタを順番に呼び出す
		if ( $this->_filters && is_array($this->_filters) )
		{
			foreach( $this->_filters as $filter ){
				$value = $filter->apply( $value );
			}
		}

		return $value;
	}

}
return __FILE__;
