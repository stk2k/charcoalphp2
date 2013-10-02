<?php
/**
* 日付値クラス
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_DateWithTime extends Charcoal_Primitive
{
	private $_timestamp;

	/*
	 *	コンストラクタ
	 */
	public function __construct( Charcoal_Integer $year, Charcoal_Integer $month, Charcoal_Integer $day, Charcoal_Integer $hour, Charcoal_Integer $minute, Charcoal_Integer $second )
	{
		parent::__construct();

		$this->_timestamp   = mktime( ui($hour), ui($minute), ui($second), ui($month), ui($day), ui($year) );
	}

	/**
	 *	unbox primitive value
	 */
	public function unbox()
	{
		return $this->_timestamp;
	}

	/*
	 *	文字列から生成
	 */
	public static function parse( $datetime_string )
	{
		$timestamp = strtotime( $datetime_string );

		if ( $timestamp === FALSE || $timestamp === -1 ){
			_throw ( new DateWithTimeFormatException( $datetime_string ) );
		}

		return self::fromTimestamp( $timestamp );
	}

	/*
	 *	UNIXタイムスタンプから生成
	 */
	public static function fromTimestamp( $timestamp )
	{
		$y = i(date( 'Y', $timestamp ));
		$m = i(date( 'n', $timestamp ));
		$d = i(date( 'j', $timestamp ));
		$h = i(date( 'H', $timestamp ));
		$i = i(date( 'i', $timestamp ));
		$s = i(date( 's', $timestamp ));

		return new DateWithTime( $y, $m, $d, $h, $i, $s );
	}

	/*
	 *	年を取得
	 */
	public function getYear()
	{
		return date( 'Y', $this->_timestamp );
	}

	/*
	 *	月を取得
	 */
	public function getMonth()
	{
		return date( 'n', $this->_timestamp );
	}

	/*
	 *	日を取得
	 */
	public function getDay()
	{
		return date( 'j', $this->_timestamp );
	}

	/*
	 *	時を取得
	 */
	public function getHour()
	{
		return date( 'H', $this->_timestamp );
	}

	/*
	 *	分を取得
	 */
	public function getMinute()
	{
		return date( 'i', $this->_timestamp );
	}

	/*
	 *	秒を取得
	 */
	public function getSecond()
	{
		return date( 's', $this->_timestamp );
	}

	/*
	 *	書式化
	 */
	public function format( Charcoal_String $format_pattern )
	{
		return date( $format_pattern, $this->_timestamp );
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return date( 'Y-m-d H:i:s', $this->_timestamp );
	}
}

