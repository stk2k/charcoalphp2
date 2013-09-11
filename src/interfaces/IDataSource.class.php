<?php
/**
* データソースを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

interface Charcoal_IDataSource extends Charcoal_ICharcoalObject
{
	/*
	 *    接続済みか
	 */
	public function isConnected();

	/*
	 *    バックエンドを取得
	 */
	public function getBackend();

	/*
	 *    接続先サーバを取得
	 */
	public function getServer();

	/*
	 *    接続ユーザ名を取得
	 */
	public function getUser();

	/*
	 *    接続パスワードを取得
	 */
	public function getPassword();

	/*
	 *    接続先データベース名を取得
	 */
	public function getDatabaseName();

	/*
	 *    接続時の文字コードを取得
	 */
	public function getCharacterSet();

	/*
	 *    自動コミット機能をON/OFF
	 */
	public function autoCommit( $on );

	/*
	 *    トランザクションを開始
	 */
	public function beginTrans();

	/*
	 *    コミットを発行
	 */
	public function commitTrans();

	/*
	 *    ロールバックを発行
	 */
	public function rollbackTrans();

	/*
	 *    接続
	 */
	public function connect( $force = FALSE );

	/*
	 *    デフォルトの接続先に接続
	 */
	public function connectDefault( $db_name_enabled = TRUE );

	/*
	 *    接続を閉じる
	 */
	public function disconnect();

	/*
	 *    SQLをそのまま発行（結果セットあり）
	 */
	public function query( $sql );

	/*
	 *    SQLをそのまま発行（結果セットなし）
	 */
	public function execute( $sql );

	/*
	 *    プリペアドステートメントの発行
	 */
	public function prepareExecute( $sql, $params = NULL );

	/*
	 *    実行結果件数取得
	 */
	function numRows( $result );

	/*
	 *    フェッチ処理（連想配列で返却）
	 */
	public function fetchAssoc( $result );

	/*
	 *    フェッチ処理（配列で返却）
	 */
	public function fetchArray( $result );

	/*
	 *   最後に実行されたAUTO_INCREMENT値を取得
	 */
	public function getLastInsertId();

}

