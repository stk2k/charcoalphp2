<?php
/**
* ベンチマーククラス
*
* PHP version 5
*
* @package    class.debug
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_Benchmark
{
	const DEFAULT_PRECISION    = 4;

	static private $start_times;

	/**
	 *  start timer
	 *  
	 *  @return integer    handle of timer
	 */
	public static function start()
	{
		static $handle = 0;

		$start_time = microtime(true);

		self::$start_times[$handle] = $start_time;

		return $handle++;
	}

	/**
	 *    stop timer
	 *  
	 *  @param integer $handle        handle of timer
	 *  @param integer $precision     precision of timer value
	 *  
	 *  @return integer      now score
	 */
	public static function stop( $handle, $precision = self::DEFAULT_PRECISION )
	{
		$start_time = isset(self::$start_times[$handle]) ? self::$start_times[$handle] : NULL;
		$stop_time = microtime(true);

		if ( $start_time === NULL ){
			_throw( new Charcoal_BenchmarkException('not started yet!') );
		}

		self::$start_times[$handle] = $stop_time;

		return round( ($stop_time - $start_time) * 1000, $precision );
	}

	/**
	 *    score
	 *  
	 *  @param integer $handle        handle of timer
	 *  @param integer $precision     precision of timer value
	 *  
	 *  @return integer      now score
	 */
	public static function score( $handle, $precision = self::DEFAULT_PRECISION )
	{
		$start_time = isset(self::$start_times[$handle]) ? self::$start_times[$handle] : NULL;
		$stop_time = microtime(true);

		if ( $start_time === NULL ){
			_throw( new Charcoal_BenchmarkException('not started yet!') );
		}

		return round( ($stop_time - $start_time) * 1000, $precision );
	}

}

