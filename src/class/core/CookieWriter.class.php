<?php
/**
* Cookie Class
*
* PHP version 5
*
* @package    class.core
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_CookieWriter extends Charcoal_Object
{
	private $expire;
	private $path;
	private $domain;
	private $secure;
	private $httponly;

	private $values;	// array

	/*
	 *	Construct object
	 */
	public function __construct()
	{
		parent::__construct();

		$this->values = array();

		// store client cookies
		if ( $_COOKIE && is_array($_COOKIE) ){
			foreach( $_COOKIE as $key => $value ){
				$this->values[$key] = $value;
			}
		}
	}

	/*
	 * Set cookie value
	 */
	public function setValue( $name, $value )
	{
		$this->values[ us($name) ] = us($value);
	}

	/*
	 * Set cookie expire time
	 */
	public function setExpire(  $expire )
	{
		$this->expire = ui($expire);
	}

	/*
	 * Get cookie expire time
	 */
	public function getExpire()
	{
		return $this->expire;
	}

	/*
	 * Set cookie path
	 */
	public function setPath( $path )
	{
		$this->path = us($path);
	}

	/*
	 * Get cookie path
	 */
	public function getPath()
	{
		return $this->path;
	}

	/*
	 * Set cookie domain
	 */
	public function setDomain( $domain )
	{
		$this->domain = us($domain);
	}

	/*
	 * Get cookie domain
	 */
	public function getDomain()
	{
		return $this->domain;
	}

	/*
	 * Set cookie secure
	 */
	public function setSecure( $secure )
	{
		$this->secure = ub($secure);
	}

	/*
	 * Get cookie secure
	 */
	public function isSecure()
	{
		return $this->secure;
	}

	/*
	 * Set cookie http only
	 */
	public function setHttpOnly( $httponly )
	{
		$this->httponly = ub($httponly);
	}

	/*
	 * Get cookie secure
	 */
	public function isHttpOnly()
	{
		return $this->httponly;
	}

	/*
	 * Write cookie to client(auto URL encoded)
	 */
	private function _write( $name, $value )
	{
		setcookie( us($name), us($value), $this->expire, $this->path, $this->domain, $this->secure, $this->httponly );
	}

	/*
	 * Write cookie to client(auto URL encoded)
	 */
	public function write( $name )
	{
		$value = $this->getValue( $name );
		$this->_write( $name, $value );
	}

	/*
	 * Write all cookies to client(auto URL encoded)
	 */
	public function writeAll()
	{
		foreach( $this->values as $name => $value ){
			$this->_write( s($name), s($value) );
		}
	}

	/*
	 * Write cookie to client(auto URL encoded)
	 */
	private function _write_raw( $name, $value )
	{
		setrawcookie( us($name), us($value), $this->expire, $this->path, $this->domain, $this->secure, $this->httponly );
	}

	/*
	 * Write cookie to client(auto URL encoded)
	 */
	public function writeRaw( $name )
	{
		$value = $this->getValue( $name );
		$this->_write_raw( $name, $value );
	}

	/*
	 * Write cookie to client(no URL encoded)
	 */
	public function writeRawAll()
	{
		foreach( $this->values as $name => $value ){
			$this->_write_raw( s($name), s($value) );
		}
	}

	/**
	 *	Get all values with keys
	 *
	 * @return array
	 */
	public function getAll()
	{
		return $this->values;
	}

}


