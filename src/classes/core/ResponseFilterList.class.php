<?php
/**
* レスポンスフィルタリスト
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ResponseFilterList
{
	var $_list;

	/*
	 *    コンストラクタ
	 */
	private function __construct()
	{
		$this->_list = array();
	}

	/*
	 * レスポンスフィルタを追加
	 */
	public function addResponseFilter( Charcoal_IResponseFilter $filter )
	{
		// インスタンスの取得
		$ins = self::getInstance();

		$ins->_list[] = $filter;
	}

	/*
	 * レスポンスフィルタを適用
	 */
	public function applyAll( $value )
	{
		// インスタンスの取得
		$ins = self::getInstance();

		// 例外ハンドラを順番に呼び出す
		foreach( $ins->_list as $filter ){
			$value = $filter->apply( $value );
		}

		return $value;
	}

}
return __FILE__;