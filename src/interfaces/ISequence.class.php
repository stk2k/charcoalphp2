<?php
/**
* リクエストを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_ISequence extends Charcoal_IProperties
{
	/*
	 *    globalパラメータを取得
	 */
	public function getGlobal( Charcoal_String $key );

	/*
	 *    localパラメータを取得
	 */
	public function getLocal( Charcoal_String $key );

	/*
	 *    パラメータを設定
	 */
	public function set( Charcoal_String $key, $value );

	/*
	 *    globalパラメータを設定
	 */
	public function setGlobal( Charcoal_String $key, $value );

	/*
	 *    localパラメータを設定
	 */
	public function setLocal( Charcoal_String $key, $value );



}

