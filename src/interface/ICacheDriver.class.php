<?php
/**
* Interface of cache driver
* 
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_ICacheDriver extends Charcoal_ICharcoalObject
{
    /**
     * Get non-typed data which is associated with a string key
     *
     * @param string $key         The key of the item to retrieve.
     */
    public function get( $key );

    /**
     * Save a value to cache
     *
     * @param string $key                   The key under which to store the value.
     * @param Charcoal_Object $value        value to save
     * @param int $duration                 specify expiration span which the cache will be removed.
     */
    public function set( $key, $value, $duration = NULL );

    /**
     * Remove a cache data
     *
     * @param string $key         The key of the item to remove. Shell wildcards are accepted.
     */
    public function delete( $key );

    /**
     * Rewrite cache expiration time
     *
     * @param string $key         The key of the item to remove. Shell wildcards are accepted.
     * @param int $duration       specify expiration span which the cache will be removed.
     */
    public function touch( $key, $duration = NULL );


}

