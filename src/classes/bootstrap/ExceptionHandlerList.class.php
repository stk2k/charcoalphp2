<?php
/**
* exception handler list
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ExceptionHandlerList
{
	static private $handlers;

	/**
	 * add exception handler
	 * 
	 * @param Charcoal_IExceptionHandler $handler       renderer to add
	 */
	public static function add( Charcoal_IExceptionHandler $handler )
	{
		self::$handlers[] = $handler;
	}

	/**
	 * initialize exception handler list
	 */
	public static function init()
	{
		if ( !self::$handlers ){
			$exception_handlers = Charcoal_Profile::getArray( s('EXCEPTION_HANDLERS') );
			if ( $exception_handlers ){
				foreach( $exception_handlers as $handler_name ){
					$handler = Charcoal_Factory::createObject( s($handler_name), s('exception_handler'), v(array()), s('Charcoal_IExceptionHandler') );
					self::$handlers[] = $handler;
				}
			}
			else{
				self::$handlers = array();
			}
		}
	}

	/*
	 * フレームワーク例外ハンドラを実行
	 */
	public static function handleFrameworkException( Charcoal_CharcoalException $e )
	{
		self::init();

		$result = b(FALSE);
		foreach( self::$handlers as $handler ){
			log_info( "system,debug,error", "exception", "calling exception handler[$handler]." );
			$handled = $handler->handleFrameworkException( $e );
			log_info( "system,debug,error", "exception", "handled: $handled" );
			$handled = b($handled);
			if ( $handled->isTrue() ){
				$result = b(TRUE);
				break;
			}
		}

		return $result;
	}

	/*
	 * 例外ハンドラを実行
	 */
	public static function handleException( Exception $e )
	{
		self::init();

		$result = b(FALSE);
		foreach( self::$handlers as $handler ){
			log_info( "system,debug,error", "exception", "calling exception handler[$handler]." );
			$handled = $handler->handleException( $e );
			log_info( "system,debug,error", "exception", "handled: $handled" );
			$handled = b($handled);
			if ( $handled->isTrue() ){
				$result = b(TRUE);
				break;
			}
		}

		return $result;
	}
}
