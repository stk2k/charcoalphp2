<?php
/**
* テーブルモデルを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

interface Charcoal_ITableModel extends Charcoal_IModel
{
	/*
	 *   テーブル名を取得
	 */
	public function getTableName();

	/*
	 *   プライマリキーフィールド名を取得
	 */
	public function getPrimaryKey();

	/**
	 *   check if new record
	 *   
	 *  @param Charcoal_DTO $dto         target record
	 */
	public function validatePrimaryKeyValue( $dto );
}

