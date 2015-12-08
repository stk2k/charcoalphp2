<?php
/**
* リダイレクトレイアウトを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IRedirectLayout
{

    /**
     *    リダイレクト時のURLを取得
     */
    public function makeRedirectURL();

}

