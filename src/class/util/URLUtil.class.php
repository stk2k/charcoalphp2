<?php
/**
* URLユーティリティクラス
*
* PHP version 5
*
* @package    class.util
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_URLUtil
{
	/*
	 *	相対パスを作成
	 */
	public static function makeRelativeURL( Charcoal_ObjectPath $obj_path, Charcoal_Properties $params = NULL )
	{
		// プロシージャキーを取得
		$proc_key = Charcoal_Profile::getString( s('PROC_KEY'), s('proc') );

		// URLを生成
		$url = '/?' . $proc_key . '=' . $obj_path->getObjectName() . "@" . $obj_path->getVirtualPath();

		// パラメータ部分
		if ( $params ){
			foreach( $params as $key => $value ){
				$url .= '&' . $key . '=' . $value;
			}
		}

		return $url;
	}

	/*
	 *	絶対パスを作成
	 */
	public static function makeAbsoluteURL( Charcoal_ObjectPath $obj_path, Charcoal_Properties $params = NULL )
	{
		// サーバ名
		$url = 'http://' . $_SERVER['SERVER_NAME'];

		// サーバのパス
		$url .= dirname($_SERVER['SCRIPT_NAME']);

		// 相対部分を追加
		$url .= self::makeRelativeURL( $obj_path, $params );

		return $url;
	}

	/*
	 *	URLとして正しいか検証
	 */
	public static function validateURL( Charcoal_String $url )
	{
		if ( preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', us($url)) )
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}


}


