<?php
/**
* 日付値クラス
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Date extends Charcoal_Primitive
{
	private $_timestamp;

	/*
	 *	コンストラクタ
	 */
	public function __construct( Charcoal_Integer $year, Charcoal_Integer $month, Charcoal_Integer $day )
	{
		parent::__construct();

		$this->_timestamp   = mktime( 0, 0, 0, ui($month), ui($day), ui($year) );
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
	public static function parse( $date_string )
	{
		$timestamp = strtotime( $date_string );

		if ( $timestamp === FALSE || $timestamp === -1 ){
			_throw ( new Charcoal_DateFormatException( $date_string ) );
		}

		return self::fromTimestamp( i($timestamp) );
	}

	/*
	 *	UNIXタイムスタンプから生成
	 */
	public static function fromTimestamp( Charcoal_Integer $timestamp )
	{
		$timestamp = ui($timestamp);

		$y = i(date( 'Y', $timestamp ));
		$m = i(date( 'n', $timestamp ));
		$d = i(date( 'j', $timestamp ));

		return new Charcoal_Date( $y, $m, $d );
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
		return date( 'Y-m-d', $this->_timestamp );
	}
}

