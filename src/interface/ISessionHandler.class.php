<?php
/**
* セッションハンドラを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_ISessionHandler extends Charcoal_ICharcoalObject
{

    /**
     * コールバック関数：オープン
     */
    public function open( $save_path, $session_name );

    /**
     * コールバック関数：クローズ
     */
    public function close();

    /**
     * コールバック関数：読み取り
     */
    public function read( $id );

    /**
     * コールバック関数：書き込み
     */
    public function write( $id, $sess_data );

    /**
     * コールバック関数：破棄
     */
    public function destroy( $id );

    /**
     * コールバック関数：ガベージコレクション
     */
    public function gc( $max_lifetime );

}

