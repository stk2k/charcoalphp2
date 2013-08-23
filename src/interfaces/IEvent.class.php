<?php
/**
* イベントを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_IEvent extends Charcoal_ICharcoalObject
{
	/**
	 * 実行優先度を取得する
	 */
	public function getPriority();

}

return __FILE__;