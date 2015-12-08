<?php
/**
* レスポンスフィルタを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IResponseFilter extends Charcoal_ICharcoalObject
{
    /**
     * セットされた値を加工する
     */
    public function apply( $value );
}

