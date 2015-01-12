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

	static private $stack = array();

	/**
	 *  start timer
	 */
	public static function start()
	{
		self::$stack[] = microtime(true);
	}

	/**
	 *    stop timer
	 *  
	 *  @param integer $precision     precision of timer value
	 *  
	 *  @return integer      now score
	 */
	public static function stop( $precision = self::DEFAULT_PRECISION )
	{
		$start = array_pop( self::$stack );
		$stop = microtime(true);

		if ( $start === NULL ){
			_throw( new Charcoal_BenchmarkException('not started yet!') );
		}

		return round( ($stop - $start) * 1000, $precision );
	}

	/**
	 *    score
	 *  
	 *  @param integer $precision     precision of timer value
	 *  
	 *  @return integer      now score
	 */
	public static function score( $precision = self::DEFAULT_PRECISION )
	{
		$start = array_pop( self::$stack );
		$stop = microtime(true);

		if ( $start === NULL ){
			_throw( new Charcoal_BenchmarkException('not started yet!') );
		}

		self::$stack[] = $start;

		return round( ($stop - $start) * 1000, $precision );
	}

}

