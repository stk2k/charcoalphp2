<?php
/**
* 文字列クラス
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_String extends Charcoal_Primitive
{
	private $_value;
	private $_encoding;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $value, $encoding = NULL )
	{
		parent::__construct();

		if ( !is_string($value) ){
			if ( $value instanceof Charcoal_Object ){
				$value = $value->toString();
			}
			else if ( is_scalar($value) ){
				$value = strval($value);
			}
			else if ( $value === NULL ){
				$value = '';
			}
		}

		if ( !is_string($value) ){
			_throw( new Charcoal_NonStringException( $value ) );
		}

		$this->_value      = $value;
		$this->_encoding   = $encoding;
	}

	/**
	 *	unbox primitive value
	 */
	public function unbox()
	{
		return $this->_value;
	}

	/*
	 *	エンコーディング変換
	 */
	public function convertEncoding( $to_encoding )
	{
		$encoding = $this->_encoding ? $this->_encoding : Profile::getString('PHP_CODE');

		// 変換元エンコーディングを決定
		switch ( $this->_encoding ){
		case 'HTML_CODE':
		case 'DB_CODE':
		case 'PHP_CODE':
		case 'LOG_CODE':
			$from_encoding = chacoal_Profile::getString( $this->_encoding );
		default:
			$from_encoding = $this->_encoding;
		}

		// 変換先エンコーディングを決定
		switch ( $to_encoding ){
		case 'HTML_CODE':
		case 'DB_CODE':
		case 'PHP_CODE':
		case 'LOG_CODE':
			$to_encoding = Profile::getString( $to_encoding );
		}

		$str = mb_convert_encoding( $this->_value, $to_encoding, $from_encoding );

		return new Charcoal_String( $str, $to_encoding );
	}

	/*
	 *	値を取得
	 */
	public function getValue()
	{
		return $this->_value;
	}

	/*
	 *	値を設定
	 */
	public function setValue( $value, $encoding = NULL )
	{
		if ( $value instanceof Charcoal_Object ){
			$value = us( $value );
		}
		else if ( !is_string($value) ){
			$value = strval($value);
		}

		$this->_value      = $value;
		$this->_encoding   = $encoding;
	}


	/*
	 *	長さを取得
	 */
	public function length()
	{
		return $this->_value && is_string($this->_value) ? strlen($this->_value) : -1;
	}

	/*
	 *	文字列で分割する
	 */
	public function split( Charcoal_String $delimiter )
	{
		$string     = $this->_value;
		$delimiter  = us( $delimiter );
		$explode    = explode( $delimiter, $string );

		return new Charcoal_Vector( $explode );
	}

	/*
	 *	正規表現で分割する
	 */
	public function splitRegEx( Charcoal_String $regex )
	{
		$string = $this->_value;
		$regex  = us( $regex );
		$matches = array();
		$split_word_list = NULL;

		if ( $cnt = preg_match_all( $regex, $string, $matches, PREG_OFFSET_CAPTURE ) ){
			$start_pos = 0;
			for($i=0;$i<$cnt;$i++){
				$match_str = $matches[$i][0];
				$match_pos = $matches[$i][1];
				$end_pos = $match_pos - 1;
				$length = $end_pos - $start_pos + 1;
				if ( $length > 0 ){
					$split_word = substr( $string, $start, $length );
					$split_word_list[] = $split_word;
				}
				$start_pos = $match_pos + strlen($match_str);
			}
		}

		return $split_word_list;
	}

	/*
	 *	比較
	 */
	public function equals( Charcoal_Object $obj )
	{
		$str1 = $this->_value;

		if ( $obj instanceof Charcoal_String ){
			$str2 = $obj->_value;
		}
		else if ( is_string($obj) ){
			$str2 = $obj;
		}
		else{
			return FALSE;
		}

		return strcmp($str1,$str2) === 0;
	}

	/*
	 *	空か
	 */
	public function isEmpty()
	{
		if ( !$this->_value ){
			return TRUE;
		}
		if ( is_string($this->_value) ){
			return strlen($this->_value) === 0;
		}
		return TRUE;
	}

	/*
	 *	大文字化
	 */
	public function toUpper()
	{
		return $this->_value ? strtoupper($this->_value) : NULL;
	}

	/*
	 *	小文字化
	 */
	public function toLower()
	{
		return $this->_value ? strtolower($this->_value) : NULL;
	}

	/*
	 *	空白を除去
	 */
	public function trim(String $charlist = NULL)
	{
		$s = $this->_value;
		$s = $charlist ?  trim($s,us($charlist)) : trim($s);
		return s($s);
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return $this->_value;
	}
}
return __FILE__;
