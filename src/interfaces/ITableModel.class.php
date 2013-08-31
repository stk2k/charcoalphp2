<?php
/**
* テーブルモデルを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
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

	/*
	 *  check if primary key field value is valid
	 */
	public function isPrimaryKeyValid( Charcoal_TableDTO $dto );
}

