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
	public static function makeRelativeURL( $sandbox, $obj_path, $params = NULL )
	{
		Charcoal_ParamTrait::validateSandbox( 1, $sandbox );
		Charcoal_ParamTrait::validateStringOrObject( 2, 'Charcoal_ObjectPath', $obj_path );
		Charcoal_ParamTrait::validateHashMap( 3, $params, TRUE );

		if ( is_string(us($obj_path)) ){
			$obj_path = new Charcoal_ObjectPath($obj_path);
		}

		// プロシージャキーを取得
		$proc_key = $sandbox->getProfile()->getString('PROC_KEY', 'proc');

		// URLを生成
		$url = '/?' . us($proc_key) . '=' . $obj_path->getObjectName() . "@" . $obj_path->getVirtualPath();

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
	public static function makeAbsoluteURL( $sandbox, $obj_path, $params = NULL )
	{
		Charcoal_ParamTrait::validateSandbox( 1, $sandbox );
		Charcoal_ParamTrait::validateStringOrObject( 2, 'Charcoal_ObjectPath', $obj_path );
		Charcoal_ParamTrait::validateHashMap( 3, $params, TRUE );

		// サーバ名
		$url = 'http://' . $_SERVER['SERVER_NAME'];

		// サーバのパス
		$url .= dirname($_SERVER['SCRIPT_NAME']);

		// 相対部分を追加
		$url .= self::makeRelativeURL( $sandbox, $obj_path, $params );

		return $url;
	}
}


