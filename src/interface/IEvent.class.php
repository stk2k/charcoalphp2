<?php
/**
* イベントを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IEvent extends Charcoal_ICharcoalComponent
{
    /**
     * 実行優先度を取得する
     */
    public function getPriority();

}

