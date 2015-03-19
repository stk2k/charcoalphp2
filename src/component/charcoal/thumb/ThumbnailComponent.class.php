<?php
/**
* Thumbmail component
*
* PHP version 5
*
* @package	component.thumb
* @author	 stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/
require_once( 'ThumbnailComponentException.class.php' );


class Charcoal_ThumbnailComponent extends Charcoal_CharcoalComponent implements Charcoal_IComponent
{
	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 *	Load bitmap
	 */
	public function create( $src_file, $dest_file, $thumb_max_width, $thumb_max_height, $image_format = IMG_JPG )
	{
		Charcoal_ParamTrait::validateString( 1, $src_file );
		Charcoal_ParamTrait::validateString( 2, $dest_file );
		Charcoal_ParamTrait::validateInteger( 3, $thumb_max_width );
		Charcoal_ParamTrait::validateInteger( 4, $thumb_max_height );
		Charcoal_ParamTrait::validateInteger( 5, $image_format );

		$src_file 			= us($src_file);
		$dest_file 			= us($dest_file);
		$thumb_max_width 	= ui($thumb_max_width);
		$thumb_max_height	= ui($thumb_max_height);

		$info = @getimagesize($src_file);

		if ($info === false) {
			_throw( new ThumbnailComponentException( s("failed to get image info:[$src_file]") ) );
		}

		// MimeTypeを調べる
		$mime = $info['mime'];
		switch ( $mime ) {
			case 'image/gif':
				$mime = $ext = 'gif';
				break;
			case 'image/png':
				$mime = $ext = 'png';
				break;
			case 'image/jpeg':
				$mime = 'jpeg';
				$ext  = 'jpg';
				break;
			default:
				_throw( new ThumbnailComponentException( s("invalid image type:[$mime]") ) );
		}

		// 元の画像の幅と高さ
		$width  = $info[0];
		$height = $info[1];

		// 元の画像のアスペクト比
		$aspect = $width / $height;

		// 幅・高さに０が指定された場合は元画像のサイズを使用
		if ( $thumb_max_width == 0 ){
			$thumb_max_width = $width;
		}
		if ( $thumb_max_height == 0 ){
			$thumb_max_height = $height;
		}

		// 変換先サイズのアスペクト比
		$max_aspect = $thumb_max_width / $thumb_max_height;

		// 画像リソースを生成
		$img = @call_user_func("imagecreatefrom{$mime}", $src_file);
		if ( $img === FALSE || !is_resource($img) ) {
			_throw( new ThumbnailComponentException( s("failed to imagecreate{$mime}($src_file)") ) );
		}

		// 最大幅・高さを超過していないかチェック・縦横比を維持して新しいサイズを定義
		if ( $aspect > $max_aspect ) {
			$thumb_width  = $thumb_max_width;
			$thumb_height = intval($thumb_width / $aspect);
		}
		else{
			$thumb_height = $thumb_max_height;
			$thumb_width  = intval($thumb_height * $aspect); 
		}

		// サムネイルを作成
		$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
		imagecopyresampled($thumb, $img, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);

		switch ( $image_format ) {
		case IMG_GIF:
			$res = imagegif( $thumb, $dest_file );
			break;
		case IMG_PNG:
			$res = imagepng( $thumb, $dest_file );
			break;
		case IMG_JPG:
			$res = imagejpeg( $thumb, $dest_file );
			break;
		default:
			_throw( new ThumbnailComponentException( "invalid image image format:[$image_format]" ) );
		}

		return array( $thumb_width, $thumb_height );
	}

}

return __FILE__;