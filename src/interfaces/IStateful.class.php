<?php
/**
* ステートフルオブジェクトを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_IStateful extends Charcoal_ICharcoalObject
{
	/**
	 * オブジェクトの内容を初期化する
	 */
	public function initContents();

	/**
	 * オブジェクトの内容をシリアライズする
	 */
	public function serializeContents();

	/**
	 * オブジェクトの内容をデシリアライズする
	 */
	public function deserializeContents( $serialize_data );
}

