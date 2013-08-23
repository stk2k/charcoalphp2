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
interface Charcoal_IRequest extends Charcoal_ICharcoalObject
{
	/*
	 *    プロシージャパスを取得
	 */
	public function getProcedurePath();

	/*
	 *    リクエストIDを取得
	 */
	public function getRequestID();

	/*
	 *  Get all keys
	 *
	 * @return array  
	 */
	public function getKeys();

	/*
	 *    パラメータを取得
	 */
	public function get( Charcoal_String $key );

	/*
	 *    すべてのパラメータをハッシュマップで取得
	 */
	public function getAll();

	/*
	 *    パラメータを設定
	 */
	public function set( Charcoal_String $key, $value );

	/*
	 *    キーがあるか
	 */
	public function keyExists( Charcoal_String $key );

	/*
	 *	配列の全要素を追加
	 */
	public function setArray( array $array );

	/*
	 *	プロパティ配列の全要素を追加
	 */
	public function setProperties( Charcoal_Properties $data );

	/*
	 *	プロパティ配列をマージ
	 */
	public function mergeProperties( Charcoal_Properties $data, Charcoal_Boolean $overwrite = NULL );

	/*
	 *    文字列パラメータを取得
	 */
	public function getString( Charcoal_String $key, Charcoal_String $defaultValue =NULL );

	/*
	 *    整数パラメータを取得
	 */
	public function getInteger( Charcoal_String $key, Charcoal_Integer $defaultValue =NULL );
	
	/*
	 *    配列パラメータを取得
	 */
	public function getArray( Charcoal_String $key, Charcoal_Vector $defaultValue =NULL );


}

return __FILE__;