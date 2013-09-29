<?php
/**
* グラフィック操作に関するユーティリティクラス
*
* PHP version 5
*
* @package    classes.util
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_GraphicsUtil
{
	/*
	 * retrieve image width and height
	 */
	public static function getImageSize( Charcoal_File $image_file )
	{
		if ( !$image_file->exists() ){
			return array( 0, 0 );
		}
		$data = getimagesize( $image_file->getPath() );
		if ( $data === FALSE ){
			_throw( new Charcoal_ImageGetSizeException( $image_file ) );
		}
		return array( $data[0], $data[1] );
	}

	/*
	 *	画像タイプを取得
	 */
	public static function getImageType( Charcoal_File $image_file )
	{
		if ( !$image_file->exists() ){
			return NULL;
		}
		$data = getimagesize( us($image_file->getPath()) );
		if ( $data === FALSE ){
			_throw( new Charcoal_ImageGetSizeException( $image_file ) );
		}
		return $data[2];
	}

	/*
	 *	画像タイプからMIMEタイプを取得
	 */
	public static function getMime( Charcoal_File $image_file )
	{
		if ( !$image_file->exists() ){
			return NULL;
		}
		$data = getimagesize( us($image_file->getPath()) );
		if ( $data === FALSE ){
			_throw( new Charcoal_ImageGetSizeException( $image_file ) );
		}
		return $data['mime'];
	}

	/*
	 *	アスペクト比を考慮して指定したサイズに収まるようなサイズを決定する
	 */
	public static function calcImageFitSize( GDImageInfo $img_info, Charcoal_Integer $dst_width, Charcoal_Integer $dst_height )
	{
		// 変換元画像サイズ
		$src_width  = $img_info->getWidth();
		$src_height = $img_info->getHeight();

		// 変換先画像サイズ
		$dst_width  = ui($dst_width);
		$dst_height = ui($dst_height);

		// 幅を揃えた場合の拡大率
		$zoom_1 = 1 / $src_width * $dst_width;

		// 高さを揃えた場合の拡大率
		$zoom_2 = 1 / $src_height * $dst_height;

		// 拡大率が小さい方がピッタリ収まるサイズ
		$zoom = min( $zoom_1, $zoom_2 );
		$width  = $src_width * $zoom;
		$height = $src_height * $zoom;

		return array( $width, $height );
	}

}


