<?php
/**
* Frontend interface of core hook
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_CoreHook
{
	static private $hook_list;
	static private $message_queue;

	/**
	 * Register hook object
	 */
	public static function register( Charcoal_String $key, Charcoal_ICoreHook $hook )
	{
		$key = us($key);

		self::$hook_list[$key] = $hook;
	}

	/**
	 * Call all hook object
	 */
	public static function processAll()
	{
		if ( !self::$hook_list || !self::$message_queue ){
			return;
		}

		foreach( self::$hook_list as $hook ){
			foreach( self::$message_queue as $msg ){
				$hook->process( $msg );
			}
		}
	}


	/**
	 * Add hook message to internal queue
	 */
	public static function pushMessage( Charcoal_CoreHookMessage $msg )
	{
		self::$message_queue[] = $msg;
	}
}

