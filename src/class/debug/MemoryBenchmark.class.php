<?php
/**
* Benchmark class Of Memory Usage
*
* PHP version 5
*
* @package    class.debug
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_MemoryBenchmark
{
	const DEFAULT_PRECISION    = 4;

	static private $benchmarks;

	/**
	 *  start timer
	 *  
	 */
	public static function start()
	{
		static $handle = 0;

		$usage_1 = memory_get_usage(true);
		$usage_2 = memory_get_usage(false);

		self::$benchmarks[$handle] = array(
				$usage_1, $usage_2, 
			);

		return $handle++;
	}

	/**
	 *    stop timer
	 *  
	 *  @param integer $handle        handle of benchmark
	 *  @param integer $unit          unit of memory usage
	 *  @param integer $precision     precision of memory usage
	 *  
	 *  @return integer      now score
	 */
	public static function stop( $handle, $unit = Charcoal_EnumMemoryUnit::UNIT_B, $precision = Charcoal_MemoryUtil::DEFAULT_PRECISION )
	{
		$start = isset(self::$benchmarks[$handle]) ? self::$benchmarks[$handle] : NULL;

		if ( $start === NULL ){
			_throw( new Charcoal_BenchmarkException('not started yet!') );
		}

		list( $start_usage_1, $start_usage_2 ) = $start;

		$score_1 = memory_get_usage(true) - $start_usage_1;
		$score_2 = memory_get_usage(false) - $start_usage_2;

		$score_1 = Charcoal_MemoryUtil::convertSize( $score_1, $unit, $precision );
		$score_2 = Charcoal_MemoryUtil::convertSize( $score_2, $unit, $precision );

		$scores = array( $score_1, $score_2 );

		self::$benchmarks[$handle] = $scores;

		return $scores;
	}

	/**
	 *    score
	 *  
	 *  @param integer $unit          unit of memory usage
	 *  @param integer $precision     precision of memory usage
	 *  
	 *  @return integer      now score
	 */
	public static function score( $handle, $unit = Charcoal_EnumMemoryUnit::UNIT_B, $precision = Charcoal_MemoryUtil::DEFAULT_PRECISION  )
	{
		$start = isset(self::$benchmarks[$handle]) ? self::$benchmarks[$handle] : NULL;

		if ( $start === NULL ){
			_throw( new Charcoal_BenchmarkException('not started yet!') );
		}

		list( $start_usage_1, $start_usage_2 ) = $start;

		$score_1 = memory_get_usage(true) - $start_usage_1;
		$score_2 = memory_get_usage(false) - $start_usage_2;

		$score_1 = Charcoal_MemoryUtil::convertSize( $score_1, $unit, $precision );
		$score_2 = Charcoal_MemoryUtil::convertSize( $score_2, $unit, $precision );

		return array( $score_1, $score_2 );
	}

}

