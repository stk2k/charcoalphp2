<?php
/**
* セッションハンドラを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_ISessionHandler extends Charcoal_ICharcoalObject
{

	/**
	 * コールバック関数：オープン
	 */
	public static function open( $save_path, $session_name );

	/**
	 * コールバック関数：クローズ
	 */
	public static function close();

	/**
	 * コールバック関数：読み取り
	 */
	public static function read( $id );

	/**
	 * コールバック関数：書き込み
	 */
	public static function write( $id, $sess_data );

	/**
	 * コールバック関数：破棄
	 */
	public static function destroy( $id );

	/**
	 * コールバック関数：ガベージコレクション
	 */
	public static function gc( $max_lifetime );

}

return __FILE__;