<?php
/**
* トランスフォーマを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_ITransformer extends Charcoal_ICharcoalObject
{
    /**
     * 変換する
     */
    public function transform( DTO $in_data, DTO $out_data, Charcoal_Properties $options = NULL );

}

