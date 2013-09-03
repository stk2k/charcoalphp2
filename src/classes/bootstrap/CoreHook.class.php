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
	static private $hooks;
	static private $queue;

	/**
	 * Register hook object
	 * 
	 * @param Charcoal_ICoreHook $hook        core hook to add
	 */
	public static function add( Charcoal_ICoreHook $hook )
	{
		self::$hooks[] = $hook;
	}

	/**
	 * pushes message to internal queue
	 * 
	 * @param Charcoal_CoreHookMessage $msg      hook message
	 * @param Charcoal_Boolean $flush_now        if true, passed message will be flushed to hook immediately.
	 */
	public static function pushMessage( Charcoal_CoreHookMessage $msg, Charcoal_Boolean $flush_now = NULL )
	{
		if ( $flush_now && $flush_now->isTrue() ){
			foreach( self::$hooks as $hook ){
				$hook->processMessage( $msg );
			}
		}
		else{
			self::$queue[] = $msg;
		}
	}

	/**
	 * flush all message to registered core hooks
	 */
	public static function flushMessages()
	{
		if ( !self::$hooks )
		{
			$hooks = Charcoal_Profile::getArray( s('CORE_HOOKS') );
			if ( $hooks ){
				foreach( $hooks as $hook_name ){
					$core_hook = Charcoal_Factory::createObject( s($hook_name), s('core_hook'), v(array()), s('Charcoal_ICoreHook') );
					self::$hooks[] = $hook;
				}
			}
			else{
				self::$hooks = array();
			}
		}

		while( $msg = array_shift(self::$queue) ){
			foreach( self::$hooks as $hook ){
				$hook->processMessage( $msg );
			}
		}
	}
}

