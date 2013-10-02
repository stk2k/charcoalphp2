<?php
/**
* ベンチマーククラス
*
* PHP version 5
*
* @package    class.debug
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Benchmark
{
	const DEFAULT_PRECISION    = 4;

	static private $stack = array();

	/**
	 *  start timer
	 *  
	 *  @param string $timer_id    key of timer to start
	 *  
	 *  @return string      now time
	 */
	public static function start()
	{
		self::$stack[] = microtime(true);
	}

	/**
	 *    stop timer
	 *  
	 *  @param string $timer_id    key of timer to stop
	 *  
	 *  @return string      now score
	 */
	public static function stop( $precision = self::DEFAULT_PRECISION )
	{
		$start = array_pop( self::$stack );
		$stop = microtime(true);

		if ( $start === NULL ){
			_throw( new Charcoal_StackEmptyException( self::$stack ) );
		}

		return round( ($stop - $start) * 1000, $precision );
	}

	/**
	 *    score
	 *  
	 *  @param string $timer_id    key of timer to stop
	 *  
	 *  @return string      now score
	 */
	public static function score( $precision = self::DEFAULT_PRECISION )
	{
		$start = array_pop( self::$stack );
		$stop = microtime(true);

		if ( $start === NULL ){
			_throw( new Charcoal_StackEmptyException( self::$stack ) );
		}

		self::$stack[] = $start;

		return round( ($stop - $start) * 1000, $precision );
	}

}

