<?php
/**
* データソースを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
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

	/**
	 *    Prepare for statement and execute query
	 *
	 * @param string|Charcoal_String $sql              SQL statement(placeholders can be included)
	 * @param array|Charcoal_HashMap $params           Parameter values for prepared statement
	 * @param array|Charcoal_HashMap $driver_options   Driver options
	 */
	public function prepareExecute( $sql, $params = NULL, $driver_options = NULL );

	/*
	 *    実行結果件数取得
	 */
	function numRows( $result );

	/*
	 *    create recordset object
	 */
	public function createRecordset( $result );

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

	/**
	 *   free result
	 *   
	 * @param mixed $result           query result
	 * 
	 * @return bool              TRUE if success, otherwise FALSE
	 */
	public function free( $result );

	/**
	 *   create recordset factory
	 *   
	 * @param integer $fetch_mode    fetch mode(defined at Charcoal_IRecordset::FETCHMODE_XXX)
	 * @param array $options         fetch mode options
	 */
	public function createRecordsetFactory( $fetch_mode = NULL, $options = NULL );
}

