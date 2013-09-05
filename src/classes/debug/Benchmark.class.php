<?php
/**
* ベンチマーククラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Benchmark
{
	static private $start;
	static private $stop;

	/**
	 *  start timer
	 *  
	 *  @param string $timer_id    key of timer to start
	 *  
	 *  @return string      now time
	 */
	public static function start( $timer_id = '' )
	{
		return self::$start[$timer_id] = microtime();
	}

	/**
	 *    stop timer
	 *  
	 *  @param string $timer_id    key of timer to stop
	 *  
	 *  @return string      now score
	 */
	public static function stop( $timer_id = '', $precision = 2 )
	{
		self::$stop[$timer_id] = microtime();
		return self::score( $timer_id, $precision );
	}

	/**
	 *  get score in msec
	 *  
	 *  @param string $timer_id      key of timer to stop
	 *  @param integer $precision    precision of score
	 *  
	 *  @return string      msec time elapsed
	 */
	public function score( $timer_id = '', $precision = 2 )
	{
		if ( !isset(self::$start[$timer_id]) ){
			_throw( new Charcoal_BenchmarkException( "timer[$timer_id] is not started yet" ) );
		}
		$start = self::$start[$timer_id];

		$stop = isset(self::$stop[$timer_id]) ? self::$stop[$timer_id] : microtime();

		$diff = Charcoal_System::diffMicrotime( $stop, $start );

		return round( $diff, $precision );
	}

}

