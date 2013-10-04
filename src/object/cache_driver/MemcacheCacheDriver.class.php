<?php
/**
*
* cache driver for memcache
*
* PHP version 5
*
* @package    objects.cache_drivers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_MemcacheCacheDriver extends Charcoal_AbstractCacheDriver
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
	public function configure( $config )
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
	 * @param string $key         The key of the item to retrieve.
	 *
	 * @return mixed              cache data
	 */
	public function get( $key )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );

		$key = us($key);

		$value = $this->_memcache->get( $key );

		$value = unserialize($value);
		return $value;
	}

	/**
	 * Save a value to cache
	 *
	 * @param string $key                   The key under which to store the value.
	 * @param Charcoal_Object $value        value to save
	 * @param int $duration                 specify expiration span which the cache will be removed.
	 */
	public function set( $key, $value, $duration = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkInteger( 3, $duration, TRUE );

		$key = us($key);
		$duration = $duration ? ui($duration) : ui($this->_default_duration);

		$value = serialize($value);

		$res = $this->_memcache->set( $key, $value );
	}

	/**
	 * Remove a cache data
	 *
	 * @param string $key         The key of the item to remove. Shell wildcards are accepted.
	 */
	public function delete( Charcoal_String $key )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );

	}

	/**
	 * Remove a cache data searched by regular expression
	 *
	 * @param string $key         The key of the item to remove. Regular expression are accepted.
	 */
	public function deleteRegEx( Charcoal_String $key )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );

	}

	/**
	 * Rewrite cache expiration time
	 *
	 * @param string $key         The key of the item to remove. Shell wildcards are accepted.
	 * @param int $duration   specify expiration span which the cache will be removed.
	 */
	public function touch( Charcoal_String $key, Charcoal_Integer $duration = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkInteger( 2, $duration, TRUE );

	}

	/**
	 * Rewrite cache expiration time searched by regular expression
	 *
	 * @param string $key         The key of the item to remove. Regular expression are accepted.
	 * @param int $duration   specify expiration span which the cache will be removed.
	 */
	public function touchRegEx( Charcoal_String $key, Charcoal_Integer $duration = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkInteger( 2, $duration, TRUE );

	}

}

