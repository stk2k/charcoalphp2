<?php
/**
* 単純なトランスフォーマ
*
* PHP version 5
*
* @package    objects.transformers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_SimpleTransformer extends Charcoal_AbstractTransformer
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
    public function transform( $in, $out, $options = NULL )
    {
        $options = up($options);

        // オプション
        $overwrite = ($options && ($options['overwrite'] === TRUE)) ? TRUE : FALSE;

        // コピー元のフィールド一覧を取得
        $vars = get_object_vars($out);

        // フィールドごとにコピー
        foreach( $vars as $key => $value )
        {
            // 変換元の値がNULLなら更新しない
            if ( !$overwrite && $value === NULL ){
                continue;
            }

            // そのままコピー
            $out->$key = $value;
        }

        return $out;
    }


}

