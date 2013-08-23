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
	private $_start;
	private $_stop;
	private $_score;


	/*
	 *    コンストラクタ
	 */
	public function __construct( $auto_start = TRUE )
	{
		$this->_start = 0;
		$this->_stop = 0;
		$this->_score = 0;

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
	public function getElapsedTime()
	{
		return $this->_stop - $this->_start;
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
  		$this->_score = round( $this->_stop - $this->_start, 5 );
		return $this->_score;
	}

	/*
	 *    現在時間を取得
	 */
	public static function nowTime()
	{ 
		list($msec,$sec) = explode(' ',microtime()); 
		return ( (float)$msec + (float)$sec );
	}

}
return __FILE__;
