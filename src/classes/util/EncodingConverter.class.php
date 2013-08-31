<?php
/**
* 文字エンコーディング変換クラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_EncodingConverter extends Charcoal_Object
{
	var $_from;
	var $_to;

	/*
	 *	コンストラクタ
	 */
	public function __construct( Charcoal_String $from, Charcoal_String $to )
	{
		$this->_from = $from;
		$this->_to = $to;
	}

	/*
	 *	文字列からコンバータを作成
	 */
	public static function fromString( Charcoal_String $from, Charcoal_String $to )
	{
		$from = self::getEncodingStringFromCode( $from );
		$to = self::getEncodingStringFromCode( $to );

		return new Charcoal_EncodingConverter( $from, $to );
	}

	/*
	 *	変換
	 */
	public function convert( Charcoal_String $str )
	{
		$str  = us($str);
		$from = us($this->_from);
		$to   = us($this->_to);

		return mb_convert_encoding( $str, $to, $from );
	}

	/*
	 *	配列を変換
	 */
	public function convertArray( Charcoal_Vector $ary )
	{
		$from = us($this->_from);
		$to   = us($this->_to);

		$new_array = array();
		foreach( uv($ary) as $key => $value ){
			$new_value = mb_convert_encoding( $value, $to, $from );
			$new_array[$key] = $new_value;
		}

		return $new_array;
	}

	/*
	 *	配列、文字列を再帰的に変換
	 */
	public function convertRecursive( $data )
	{
		if ( $data == NULL ){
			_throw( new NullPointerException( $ary ) );
		}
		if ( is_array($data) ){
			foreach( $data as $key => $value ){
				if ( $value ){
					$value = $this->convertRecursive( $value );
					$data[$key] = $value;
				}
			}
		}
		else if ( is_string($data) ){
			$data = $this->convert( $data );
		}

		return $data;
	}

	/*
	 *	変換タイプを取得
	 */
	public function getType()
	{
		return $this->_type;
	}

	/*
	 *	変換元文字コードを取得
	 */
	public function getFromEncoding()
	{
		return $this->_from;
	}

	/*
	 *	変換先文字コードを取得
	 */
	public function getToEncoding()
	{
		return $this->_to;
	}

	/*
	 *	エンコーディング文字列を取得
	 */
	private static function getEncodingStringFromCode( Charcoal_String $encoding )
	{
		$encoding_string = null;

		$encoding = us($encoding->toUpper());

		switch( $encoding ){

		// MAIL
		case 'MAIL':
			$encoding_string = s('ISO-2022-JP');							break;

		case 'JIS':
			$encoding_string = s('JIS');									break;

		case 'SJIS':
		case 'SHIFT_JIS':
			$encoding_string = s('SJIS');									break;

		case 'UTF8':
			$encoding_string = s('UTF8');									break;

		default:
			$encoding_string = Charcoal_Profile::getString( s($encoding . '_CODE') );			break;
		}

		if ( !$encoding_string ){
			_throw( new Charcoal_InvalidEncodingCodeException( s($encoding) ) );
		}

		return $encoding_string;
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return "[EncodingConverter]" . us($this->_from) . " => " . us($this->_to);
	}

}

