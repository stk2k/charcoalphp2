<?php
/**
* モデルを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IModel extends Charcoal_ICharcoalObject
{
	/**
	 * モデルIDを取得
	 */
	public function getModelID();

	/**
	 *	get all field names
	 */
	public function getFieldList();

	/**
	 * フィールドが存在するか
	 */
	public function fieldExists( $field );

	/*
	 *   フィールドのデフォルト値を取得
	 */
	public function getDefaultValue( $field );

	/*
	 *   モデル固有のDTOを作成
	 */
	public function createDTO( $values = array() );
}

