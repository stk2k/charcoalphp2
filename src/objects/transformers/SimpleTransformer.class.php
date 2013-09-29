<?php
/**
* 単純なトランスフォーマ
*
* PHP version 5
*
* @package    objects.transformers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_SimpleTransformer extends Charcoal_AbstractTransformer
{
	/**
	 * 変換する
	 */
	public function transform( DTO $in_data, DTO $out_data, Charcoal_Properties $options = NULL )
	{
		$options = up($options);

		// オプション
		$overwrite = ($options && ($options['overwrite'] === TRUE)) ? TRUE : FALSE;

		// コピー元のフィールド一覧を取得
		$vars = get_object_vars($out_data);

		// フィールドごとにコピー
		foreach( $vars as $key => $value )
		{
			// 変換元の値がNULLなら更新しない
			if ( !$overwrite && $value === NULL ){
				continue;
			}

			// そのままコピー
			$out_data->$key = $value;
		}

		return $out_data;
	}


}

