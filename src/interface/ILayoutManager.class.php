<?php
/**
* レイアウトマネージャを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_ILayoutManager extends Charcoal_ICharcoalObject
{

	/**
	 * 名前からレイアウトを取得する
	 */
	public function getLayout( $layout_name );
}

