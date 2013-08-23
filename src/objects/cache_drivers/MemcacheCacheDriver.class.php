<?php
/**
*
* cache driver for memcache
*
* PHP version 5
*
* @package    config_providers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_MemcacheCacheDriver extends Charcoal_CharcoalObject implements Charcoal_ICacheDriver
{
	private $_memcache;
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

		$this->_memcache = new Memcache();
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

		$this->_memcache->addServer( us($this->_host), ui($this->_port), TRUE, ui($this->_weight) );

		log_info( "system, debug, cache", "cache", "server added." );
	}

	/**
	 * Get non-typed data which is associated with a string key
	 *
	 * @param Charcoal_String $key         The key of the item to retrieve.
	 */
	public function get( Charcoal_String $key )
	{
		$key = us($key);

		$value = $this->_memcache->get( $key );

		$value = unserialize($value);
		return $value;
	}

	/**
	 * Save a value to cache
	 *
	 * @param Charcoal_String $key         The key under which to store the value.
	 * @param Charcoal_Integer $duration   specify expiration span which the cache will be removed.
	 */
	public function set( Charcoal_String $key, Charcoal_Object $value, Charcoal_Integer $duration = NULL )
	{
		$key = us($key);
		$duration = $duration ? ui($duration) : ui($this->_default_duration);

		$value = serialize($value);

		$res = $this->_memcache->set( $key, $value );
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

}
return __FILE__;
