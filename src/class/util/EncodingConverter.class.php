<?php
/**
* 文字エンコーディング変換クラス
*
* PHP version 5
*
* @package    class.util
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_EncodingConverter extends Charcoal_Object
{
	private $from;
	private $to;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $from, $to )
	{
//		Charcoal_ParamTrait::checkString( 1, $from );
//		Charcoal_ParamTrait::checkString( 2, $to );

		$this->from = $from;
		$this->to = $to;
	}

	/*
	 *	文字列からコンバータを作成
	 */
	public static function fromString( $sandbox, $from, $to )
	{
//		Charcoal_ParamTrait::checkSandbox( 1, $sandbox );
//		Charcoal_ParamTrait::checkString( 2, $from );
//		Charcoal_ParamTrait::checkString( 3, $to );

		$from = self::getEncodingStringFromCode( $sandbox, $from );
		$to = self::getEncodingStringFromCode( $sandbox, $to );

		return new Charcoal_EncodingConverter( $from, $to );
	}

	/*
	 *	変換
	 */
	public function convert( $str )
	{
//		Charcoal_ParamTrait::checkString( 1, $str );

		$str  = us($str);
		$from = us($this->from);
		$to   = us($this->to);

		return mb_convert_encoding( $str, $to, $from );
	}

	/*
	 *	配列を変換
	 */
	public function convertArray( $ary )
	{
//		Charcoal_ParamTrait::checkVector( 1, $ary );

		$from = us($this->from);
		$to   = us($this->to);

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
		return $this->from;
	}

	/*
	 *	変換先文字コードを取得
	 */
	public function getToEncoding()
	{
		return $this->to;
	}

	/*
	 *	エンコーディング文字列を取得
	 */
	private static function getEncodingStringFromCode( $sandbox, $encoding )
	{
//		Charcoal_ParamTrait::checkSandbox( 1, $sandbox );
//		Charcoal_ParamTrait::checkString( 2, $encoding );

		$encoding_string = null;

		$encoding = strtoupper( $encoding );

		switch( $encoding ){

		// MAIL
		case 'MAIL':
			$encoding_string = 'ISO-2022-JP';
			break;

		case 'JIS':
			$encoding_string = 'JIS';
			break;

		case 'SJIS':
		case 'SHIFT_JIS':
			$encoding_string = 'SJIS';
			break;

		case 'UTF8':
			$encoding_string = 'UTF8';
			break;

		default:
			$encoding_string = $sandbox->getProfile()->getString( $encoding . '_CODE' );
			break;
		}

		if ( empty($encoding_string) ){
			_throw( new Charcoal_InvalidEncodingCodeException( $encoding ) );
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
		return "[EncodingConverter]" . us($this->from) . " => " . us($this->to);
	}

}

