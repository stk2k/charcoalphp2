<?php
/**
* frontend interface of cache
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Cache extends Charcoal_Object
{
	private static $drivers;

	/*
	 *	check if a logger is registered
	 */
	public static function isRegistered( Charcoal_String $key )
	{
		$key = $key->getValue();
		return isset(self::$drivers[$key]);
	}

	/*
	 *	register a logger
	 */
	public static function register( Charcoal_String $key, Charcoal_ICacheDriver $cache_driver )
	{
		$key = $key->getValue();

		// set a logger to array
		self::$drivers[$key] = $cache_driver;
	}

	/**
	 * Get non-typed data which is associated with a string key
	 *
	 * @param Charcoal_String $key         The key of the item to retrieve.
	 */
	public function get( Charcoal_String $key )
	{
		$driver_list = self::$drivers;

		if ( $driver_list && is_array($driver_list) ){
			foreach( $driver_list as $driver ){
				$ret = $driver->get( $key );
				if ( FALSE !== $ret ){
					return $ret;
				}
			}
		}

		return FALSE;
	}

	/**
	 * Save a value to cache
	 *
	 * @param Charcoal_String $key         The key under which to store the value.
	 * @param Charcoal_Integer $duration   specify expiration span which the cache will be removed.
	 */
	public function set( Charcoal_String $key, Charcoal_Object $value, Charcoal_Integer $duration = NULL )
	{
		$driver_list = self::$drivers;

		if ( $driver_list && is_array($driver_list) ){
			foreach( $driver_list as $driver ){
				if ( $duration )
					$driver->set( $key, $value, $duration );
				else
					$driver->set( $key, $value );
			}
		}

		return $value;
	}

	/**
	 * Remove a cache data
	 *
	 * @param Charcoal_String $key         The key of the item to remove. Shell wildcards are accepted.
	 * @param Charcoal_Boolean $regEx      specify regular expression in $key parameter, default is NULL which means FALSE.
	 */
	public function delete( Charcoal_String $key )
	{
		$driver_list = self::$drivers;

		if ( $driver_list && is_array($driver_list) ){
			foreach( $driver_list as $driver ){
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
	public function deleteRegEx( Charcoal_String $key )
	{
		$driver_list = self::$drivers;

		if ( $driver_list && is_array($driver_list) ){
			foreach( $driver_list as $driver ){
				$driver->deleteRegEx( $key );
			}
		}
	}

	/**
	 * Rewrite cache expiration time
	 *
	 * @param Charcoal_String $key         The key of the item to remove. Shell wildcards are accepted.
	 * @param Charcoal_Integer $duration   specify expiration span which the cache will be removed.
	 */
	public function touch( Charcoal_String $key, Charcoal_Integer $duration = NULL )
	{
		$driver_list = self::$drivers;

		if ( $driver_list && is_array($driver_list) ){
			foreach( $driver_list as $driver ){
				if ( $duration )
					$driver->touch( $key, $duration );
				else
					$driver->touch( $key );
			}
		}
	}

	/**
	 * Rewrite cache expiration time searched by regular expression
	 *
	 * @param Charcoal_String $key         The key of the item to remove. Regular expression are accepted.
	 * @param Charcoal_Integer $duration   specify expiration span which the cache will be removed.
	 */
	public function touchRegEx( Charcoal_String $key, Charcoal_Integer $duration = NULL )
	{
		$driver_list = self::$drivers;

		if ( $driver_list && is_array($driver_list) ){
			foreach( $driver_list as $driver ){
				if ( $duration )
					$driver->touchRegEx( $key, $duration );
				else
					$driver->touchRegEx( $key );
			}
		}
	}

}
return __FILE__;
