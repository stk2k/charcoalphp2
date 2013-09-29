<?php
/**
* デバッグプロファイラ
*
* PHP version 5
*
* @package    classes.debug
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_DebugProfiler
{
	static $obj_cnt;

	/*
	 *	初期化
	 */
	private static function _init()
	{
		if ( !self::$obj_cnt ){
			self::$obj_cnt = array();
		}
	}

	/**
	 *	オブジェクトの生成カウントを増加
	 */
	public static function incrementObjectCount( $name )
	{
		self::_init();
	
		if ( isset(self::$obj_cnt[$name]) ){
			self::$obj_cnt[$name] ++;
		}
		else{
			self::$obj_cnt[$name] = 1;
		}
	}


	/**
	 *	オブジェクトの生成カウントを取得
	 */
	public static function getObjectCount( $name )
	{
		self::_init();

		return isset(self::$obj_cnt[$name]) ? self::$obj_cnt[$name] : 0;
	}

	/**
	 *	登録されているオブジェクトの名称一覧を取得
	 */
	public static function getObjectNameList()
	{
		self::_init();

		$_obj_cnt = self::$obj_cnt;
		ksort( $_obj_cnt, SORT_STRING );

		return array_keys($_obj_cnt);
	}
}


