<?php
/**
* Cookie Class
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Cookie extends Charcoal_Object
{
	var $_expire;
	var $_path;
	var $_domain;
	var $_secure;
	var $_httponly;

	var $_values;	// array

	/*
	 *	Construct object
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_values = array();

		// store client cookies
		if ( $_COOKIE && is_array($_COOKIE) ){
			foreach( $_COOKIE as $key => $value ){
				$this->_values[$key] = $value;
			}
		}
	}

	/*
	 * Get cookie value keys
	 */
	public function getKeys()
	{
		return $this->_value->getKeys();
	}

	/*
	 * Set cookie value
	 */
	public function setValue( Charcoal_String $name, Charcoal_String $value )
	{
		$this->_values[ us($name) ] = us($value);
	}

	/*
	 * Get cookie value
	 */
	public function getValue( Charcoal_String $name )
	{
		$name = us($name);
		return isset($this->_values[$name]) ? $this->_values[$name] : NULL;
	}

	/*
	 * Set cookie expire time
	 */
	public function setExpire( Charcoal_Integer $expire )
	{
		$this->_expire = ui($expire);
	}

	/*
	 * Get cookie expire time
	 */
	public function getExpire()
	{
		return $this->_expire;
	}

	/*
	 * Set cookie path
	 */
	public function setPath( Charcoal_String $path )
	{
		$this->_path = us($path);
	}

	/*
	 * Get cookie path
	 */
	public function getPath()
	{
		return $this->_path;
	}

	/*
	 * Set cookie domain
	 */
	public function setDomain( Charcoal_String $domain )
	{
		$this->_domain = us($domain);
	}

	/*
	 * Get cookie domain
	 */
	public function getDomain()
	{
		return $this->_domain;
	}

	/*
	 * Set cookie secure
	 */
	public function setSecure( Charcoal_Boolean $secure )
	{
		$this->_secure = ub($secure);
	}

	/*
	 * Get cookie secure
	 */
	public function isSecure()
	{
		return $this->_secure;
	}

	/*
	 * Set cookie http only
	 */
	public function setHttpOnly( Charcoal_Boolean $httponly )
	{
		$this->_httponly = ub($httponly);
	}

	/*
	 * Get cookie secure
	 */
	public function isHttpOnly()
	{
		return $this->_httponly;
	}

	/*
	 * Write cookie to client(auto URL encoded)
	 */
	private function _write( Charcoal_String $name, Charcoal_String $value )
	{
		setcookie( us($name), us($value), $this->_expire, $this->_path, $this->_domain, $this->_secure, $this->_httponly );
	}

	/*
	 * Write cookie to client(auto URL encoded)
	 */
	public function write( Charcoal_String $name )
	{
		$value = $this->getValue( $name );
		$this->_write( $name, $value );
	}

	/*
	 * Write all cookies to client(auto URL encoded)
	 */
	public function writeAll()
	{
		foreach( $this->_values as $name => $value ){
			$this->_write( s($name), s($value) );
		}
	}

	/*
	 * Write cookie to client(auto URL encoded)
	 */
	private function _write_raw( Charcoal_String $name, Charcoal_String $value )
	{
		setrawcookie( us($name), us($value), $this->_expire, $this->_path, $this->_domain, $this->_secure, $this->_httponly );
	}

	/*
	 * Write cookie to client(auto URL encoded)
	 */
	public function writeRaw( Charcoal_String $name )
	{
		$value = $this->getValue( $name );
		$this->_write_raw( $name, $value );
	}

	/*
	 * Write cookie to client(no URL encoded)
	 */
	public function writeRawAll()
	{
		foreach( $this->_values as $name => $value ){
			$this->_write_raw( s($name), s($value) );
		}
	}

	/*
	 * convert to array
	 * 
	 * @return array
	 */
	public function toArray()
	{
		return $this->_values;
	}

}

return __FILE__;
