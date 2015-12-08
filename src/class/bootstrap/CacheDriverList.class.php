<?php
/**
* frontend interface of cache
*
* PHP version 5
*
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_CacheDriverList extends Charcoal_Object
{
    private $drivers;
    private $init;
    private $sandbox;

    /**
     *  Constructor
     */
    public function __construct( $sandbox )
    {
//        Charcoal_ParamTrait::validateSandbox( 1, $sandbox );

        $this->sandbox = $sandbox;

        parent::__construct();
    }

    /**
     *  initialize instance
     */
    public function init()
    {
        if ( !$this->init ){

            $cache_drivers = $this->sandbox->getProfile()->getArray( 'CACHE_DRIVERS', array('file') );

            foreach( $cache_drivers as $driver_name ){
                $driver = $this->sandbox->createObject( $driver_name, 'cache_driver', array(), 'Charcoal_ICacheDriver' );
                $this->drivers[] = $driver;
            }
            $this->init = TRUE;
        }
    }

    /*
     *    add a logger
     */
    public function add( Charcoal_ICacheDriver $cache_driver )
    {
        $this->drivers[] = $cache_driver;
    }

    /**
     * Get non-typed data which is associated with a string key
     *
     * @param string $key         The key of the item to retrieve.
     *
     * @return mixed              cache data
     */
    public  function getCache( $key )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );

        $this->init();

        $key = us($key);

        foreach( $this->drivers as $driver )
        {
            $ret = $driver->get( $key );
            if ( FALSE !== $ret ){
                return $ret;
            }
        }

        return FALSE;
    }

    /**
     * Save a value to cache
     *
     * @param string $key                   The key under which to store the value.
     * @param Charcoal_Object $value        value to save
     * @param int $duration                 specify expiration span which the cache will be removed.
     */
    public function setCache( $key, $value, $duration = NULL )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validateInteger( 3, $duration, TRUE );

        $this->init();

        foreach( $this->drivers as $driver ){
            if ( $duration )
                $driver->set( $key, $value, $duration );
            else
                $driver->set( $key, $value );
        }
    }

    /**
     * Remove a cache data
     *
     * @param Charcoal_String $key         The key of the item to remove. Shell wildcards are accepted.
     */
    public function deleteCache( $key )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );

        $this->init();

        if ( is_array($this->drivers) ){
            foreach( $this->drivers as $driver ){
                $driver->delete( $key );
            }
        }
    }


    /**
     * Remove a cache data searched by regular expression
     *
     * @param Charcoal_String $key         The key of the item to remove. Regular expression are accepted.
     * @param Charcoal_Boolean $regEx      specify regular expression in $key parameter, default is NULL which means FALSE.
     */
    public function deleteRegEx( $key )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );

        $this->init();

        foreach( $this->drivers as $driver ){
            $driver->deleteRegEx( $key );
        }
    }

    /**
     * Rewrite cache expiration time
     *
     * @param string $key         The key of the item to remove. Shell wildcards are accepted.
     * @param int $duration   specify expiration span which the cache will be removed.
     */
    public function touch( $key, $duration = NULL )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );
//        Charcoal_ParamTrait::validateInteger( 2, $duration, TRUE );

        $this->init();

        foreach( $this->drivers as $driver ){
            if ( $duration )
                $driver->touch( $key, $duration );
            else
                $driver->touch( $key );
        }
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

        $this->init();

        foreach( $this->drivers as $driver ){
            if ( $duration )
                $driver->touchRegEx( $key, $duration );
            else
                $driver->touchRegEx( $key );
        }
    }

}

