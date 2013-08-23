<?php
/**
* リダイレクトレイアウトを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_IRedirectLayout
{

	/**
	 *	リダイレクト時のURLを取得
	 */
	public function makeRedirectURL();

}

return __FILE__;