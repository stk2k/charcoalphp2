<?php
/**
*
* cache driver for memcached
*
* PHP version 5
*
* @package    config_providers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_MemcachedCacheDriver extends Charcoal_CharcoalObject implements Charcoal_ICacheDriver
{
	private $_memcached;
	private $_host;
	private $_port;
	private $_weight;
	private $_default_duration;

	/*
	 *	Construct object
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_memcached = new Memcached();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
		parent::configure( $config );

		$this->_host              = $config->getString( s('host'), s('localhost') );
		$this->_port              = $config->getInteger( s('port'), i(11211) );
		$this->_weight            = $config->getInteger( s('weight'), i(100) );
		$this->_default_duration  = $config->getInteger( s('default_duration'), i(0) );

		log_debug( "system, debug, cache", "cache", "host=[{$this->_host}]" );
		log_debug( "system, debug, cache", "cache", "port=[{$this->_port}]" );
		log_debug( "system, debug, cache", "cache", "weight=[{$this->_weight}]" );
		log_debug( "system, debug, cache", "cache", "default_duration=[{$this->_default_duration}]" );

		$this->_memcached->addServer( us($this->_host), ui($this->_port), ui($this->_weight) );

		log_info( "system, debug, cache", "cache", "server added." );
	}

	/**
	 * Get non-typed data which is associated with a string key
	 *
	 * @param Charcoal_String $key         The key of the item to retrieve.
	 */
	public function get( Charcoal_String $key )
	{
		return $this->_memcached->get( us($key) );
	}

	/**
	 * Save a value to cache
	 *
	 * @param Charcoal_String $key         The key under which to store the value.
	 * @param Charcoal_Integer $duration   specify expiration span which the cache will be removed.
	 */
	public function set( Charcoal_String $key, Charcoal_Object $value, Charcoal_Integer $duration = NULL )
	{
		$duration = $duration ? ui($duration) : ui($this->_default_duration);

		$res = $this->_memcached->set( us($key), $value, $duration );
		if ( !$res ){
			$result_code = $this->_memcached->getResultCode();
			_throw( new Charcoal_CacheDriverException( s('memcached'), s("set failed. result code=[$result_code]") ) );
		}
	}

	/**
	 * Remove a cache data
	 *
	 * @param Charcoal_String $key         The key of the item to remove. Shell wildcards are accepted.
	 * @param Charcoal_Boolean $regEx      specify regular expression in $key parameter, default is NULL which means FALSE.
	 */
	public function delete( Charcoal_String $key, Charcoal_Boolean $regEx = NULL )
	{
	}

	/**
	 * Remove a cache data searched by regular expression
	 *
	 * @param Charcoal_String $key         The key of the item to remove. Regular expression are accepted.
	 * @param Charcoal_Boolean $regEx      specify regular expression in $key parameter, default is NULL which means FALSE.
	 */
	public function deleteRegEx( Charcoal_String $key )
	{
	}

	/**
	 * Rewrite cache expiration time
	 *
	 * @param Charcoal_String $key         The key of the item to remove. Shell wildcards are accepted.
	 * @param Charcoal_Integer $duration   specify expiration span which the cache will be removed.
	 */
	public function touch( Charcoal_String $key, Charcoal_Integer $duration = NULL )
	{
	}

	/**
	 * Rewrite cache expiration time searched by regular expression
	 *
	 * @param Charcoal_String $key         The key of the item to remove. Regular expression are accepted.
	 * @param Charcoal_Integer $duration   specify expiration span which the cache will be removed.
	 */
	public function touchRegEx( Charcoal_String $key, Charcoal_Integer $duration = NULL )
	{
}

