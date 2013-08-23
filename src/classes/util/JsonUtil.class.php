<?php
/**
* JSONデータを扱うクラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_JsonUtil
{
	/**
	 *	配列データをJSONデータとして出力（文字列）
	 *
	 */
	public static function encode( $value, Charcoal_String $encoding_str = NULL )
	{
		$value = System::convertArrayRecursive($value);

		if ( $encoding_str ){
			$conv = Charcoal_EncodingConverter::fromString( $encoding_str );

			$from = $conv->getFromEncoding();
			$to = $conv->getToEncoding();

			$value = System::convertEncodingRecursive( $value, us($to), us($from) );
		}

		return json_encode( $value );
	}

}

return __FILE__;
