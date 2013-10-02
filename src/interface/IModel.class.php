<?php
/**
* モデルを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
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
	public function fieldExists( Charcoal_String $field_name );

	/*
	 *   フィールドのデフォルト値を取得
	 */
	public function getDefaultValue( Charcoal_String $field );

	/*
	 *   モデル固有のDTOを作成
	 */
	public function createDTO( $values = array() );
}
