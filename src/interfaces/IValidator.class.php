<?php
/**
* バリデータを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_IValidator extends Charcoal_ICharcoalObject
{

	/**
	 * 変数値をバリデートする
	 */
	public function validate( $value );
}

