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
     * transform DTO
     *
     * @param Charcoal_DTO $in
     * @param Charcoal_DTO $out
     * @param array $options
     *
     * @return Charcoal_DTO
     */
    public function transform( $in, $out, $options = NULL );

}

