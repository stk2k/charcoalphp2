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
	static private $stack = array();

	/**
	 *  start timer
	 *  
	 */
	public static function start()
	{
		self::$stack[] = memory_get_peak_usage(true);
	}

	/**
	 *    stop timer
	 *  
	 *  @param integer $unit          unit of memory usage
	 *  @param integer $precision     precision of memory usage
	 *  
	 *  @return integer      now score
	 */
	public static function stop( $unit = Charcoal_EnumMemoryUnit::UNIT_B, $precision = Charcoal_MemoryUtil::DEFAULT_PRECISION )
	{
		$start = array_pop( self::$stack );
		$stop = memory_get_peak_usage(true);

		if ( $start === NULL ){
			_throw( new Charcoal_BenchmarkException('not started yet!') );
		}

		return Charcoal_MemoryUtil::convertSize( $stop - $start, $unit, $precision );
	}

	/**
	 *    score
	 *  
	 *  @param integer $unit          unit of memory usage
	 *  @param integer $precision     precision of memory usage
	 *  
	 *  @return integer      now score
	 */
	public static function score( $unit = Charcoal_EnumMemoryUnit::UNIT_B, $precision = Charcoal_MemoryUtil::DEFAULT_PRECISION  )
	{
		$start = array_pop( self::$stack );
		$stop = memory_get_peak_usage(true);

		if ( $start === NULL ){
			_throw( new Charcoal_BenchmarkException('not started yet!') );
		}
		
		self::$stack[] = $start;

		return Charcoal_MemoryUtil::convertSize( $stop - $start, $unit, $precision );
	}

}

