<?php
/**
*
* cache driver for memcached
*
* PHP version 5
*
* @package    objects.cache_drivers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_MemcachedCacheDriver extends Charcoal_AbstractCacheDriver
{
    private $_memcached;
    private $_host;
    private $_port;
    private $_weight;
    private $_default_duration;

    /*
     *    Construct object
     */
    public function __construct()
    {
        parent::__construct();

        $this->_memcached = new Memcached();
    }

    /**
     * Initialize instance
     *
     * @param array $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );
        
        $config = new Charcoal_HashMap($config);

        $this->_host              = $config->getString( 'host', 'localhost' );
        $this->_port              = $config->getInteger( 'port', 11211 );
        $this->_weight            = $config->getInteger( 'weight', 100 );
        $this->_default_duration  = $config->getInteger( 'default_duration', 0 );

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
     * @param string $key         The key of the item to retrieve.
     */
    public function get( $key )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );

        return $this->_memcached->get( us($key) );
    }

    /**
     * Save a value to cache
     *
     * @param string $key                The key under which to store the value.
     * @param Charcoal_Object $value     value to save
     * @param int $duration              specify expiration span which the cache will be removed.
     */
    public function set( $key, Charcoal_Object $value, $duration = NULL )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validateInteger( 3, $duration, TRUE );

        $duration = $duration ? ui($duration) : ui($this->_default_duration);

        $res = $this->_memcached->set( us($key), $value, $duration );
        if ( !$res ){
            $result_code = $this->_memcached->getResultCode();
            _throw( new Charcoal_CacheDriverException( 'memcached', "set failed. result code=[$result_code]" ) );
        }
    }

    /**
     * Remove a cache data
     *
     * @param string $key         The key of the item to remove. Shell wildcards are accepted.
     */
    public function delete( Charcoal_String $key, Charcoal_Boolean $regEx = NULL )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
    }

    /**
     * Remove a cache data searched by regular expression
     *
     * @param string $key         The key of the item to remove. Regular expression are accepted.
     */
    public function deleteRegEx( Charcoal_String $key )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
    }

    /**
     * Rewrite cache expiration time
     *
     * @param string $key         The key of the item to remove. Shell wildcards are accepted.
     * @param int $duration       specify expiration span which the cache will be removed.
     */
    public function touch( Charcoal_String $key, Charcoal_Integer $duration = NULL )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validateInteger( 2, $duration, TRUE );
    }

    /**
     * Rewrite cache expiration time searched by regular expression
     *
     * @param string $key         The key of the item to remove. Regular expression are accepted.
     * @param int $duration   specify expiration span which the cache will be removed.
     */
    public function touchRegEx( $key, $duration = NULL )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validateInteger( 2, $duration, TRUE );
    }
}

