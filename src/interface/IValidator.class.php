<?php
/**
* バリデータを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IValidator extends Charcoal_ICharcoalObject
{

	/**
	 * 変数値をバリデートする
	 */
	public function validate( $value );
}

