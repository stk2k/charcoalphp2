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
	private $_precision;
	private $_start;
	private $_stop;
	private $_score;


	/*
	 *    コンストラクタ
	 */
	public function __construct( $precision = 2, $auto_start = TRUE )
	{
		$this->_precision = $precision;

		if ( $auto_start ){
			$this->start();
		}
	}

	/*
	 *    スコア
	 */
	public function getScore()
	{
		return $this->_score;
	}

	/*
	 *    経過時間
	 */
	public function nowScore()
	{
  		return round( self::nowTime() - $this->_start, $this->_precision );
	}

	/*
	 *    開始
	 */
	public function start()
	{
		$this->_start = self::nowTime();
	}

	/*
	 *    終了
	 */
	public function stop()
	{
		$this->_stop = self::nowTime();
  		$this->_score = round( $this->_stop - $this->_start, $this->_precision );
		return $this->_score;
	}

	/*
	 *    現在時間を取得
	 */
	public static function nowTime()
	{ 
		return microtime(true) * 1000;
	}

}

