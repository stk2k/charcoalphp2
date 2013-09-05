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

	private static $init;

	/**
	 *  initialize instance
	 */
	public static function init()
	{
		if ( !self::$init ){

			$cache_drivers = Charcoal_Profile::getArray( s('CACHE_DRIVERS'), v(array('file')) );

			foreach( $cache_drivers as $driver_name ){
				$driver = Charcoal_Factory::createObject( s($driver_name), s('cache_driver'), v(array()), s('Charcoal_ICacheDriver') );
				self::$drivers[] = $driver;
			}
			self::$init = TRUE;
		}
	}

	/*
	 *	add a logger
	 */
	public static function add( Charcoal_ICacheDriver $cache_driver )
	{
		self::$drivers[] = $cache_driver;
	}

	/**
	 * Get non-typed data which is associated with a string key
	 *
	 * @param Charcoal_String $key         The key of the item to retrieve.
	 *
	 * @return mixed                       cache data
	 */
	public  static function get( Charcoal_String $key )
	{
		self::init();

		foreach( self::$drivers as $driver )
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
	 * @param Charcoal_String $key         The key under which to store the value.
	 * @param Charcoal_Integer $duration   specify expiration span which the cache will be removed.
	 */
	public static function set( Charcoal_String $key, Charcoal_Object $value, Charcoal_Integer $duration = NULL )
	{
		self::init();

		foreach( self::$drivers as $driver ){
			if ( $duration )
				$driver->set( $key, $value, $duration );
			else
				$driver->set( $key, $value );
		}

		return $value;
	}

	/**
	 * Remove a cache data
	 *
	 * @param Charcoal_String $key         The key of the item to remove. Shell wildcards are accepted.
	 * @param Charcoal_Boolean $regEx      specify regular expression in $key parameter, default is NULL which means FALSE.
	 */
	public static function delete( Charcoal_String $key )
	{
		self::init();

		foreach( self::$drivers as $driver ){
			$driver->delete( $key );
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
		self::init();

		foreach( self::$drivers as $driver ){
			$driver->deleteRegEx( $key );
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
		self::init();

		foreach( self::$drivers as $driver ){
			if ( $duration )
				$driver->touch( $key, $duration );
			else
				$driver->touch( $key );
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
		self::init();

		foreach( self::$drivers as $driver ){
			if ( $duration )
				$driver->touchRegEx( $key, $duration );
			else
				$driver->touchRegEx( $key );
		}
	}

}

