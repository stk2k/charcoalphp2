<?php
/**
* ファイルシステム操作に関するユーティリティクラス
*
* PHP version 5
*
* @package    extended
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_FileSystemUtil
{
	/*
	 *	ファイルコピー
	 */
	public static function copyFile( Charcoal_File $src, Charcoal_File $dest )
	{
		// コピー元ファイルの確認
		if ( !$src->isFile() ){
			_throw( new Charcoal_FileSystemException( s('copy'), s( $src->getAbsolutePath() . ' is not file') ) );
		}
		if ( !$src->isReadable() ){
			_throw( new Charcoal_FileSystemException( s('copy'), s( $src->getAbsolutePath() . ' is not readable') ) );
		}

		// コピー先の確認
		$dir = $dest->getDir();
		if ( !$dir->isDir() ){
			_throw( new Charcoal_FileSystemException( s('copy'), s( $dir->getAbsolutePath() . ' is not directory') ) );
		}

		$src = us( $src->getPath() );
		$dest = us( $dest->getPath() );

		$result = copy( $src, $dest );

		if ( false === $result ){
			_throw( new Charcoal_FileSystemException( s('copy'), s("[src]$src [dest]$dest") ) );
		}
	}

	/*
	 *	拡張子を取得
	 */
	public static function getExtension( Charcoal_String $path_text )
	{
		$info = pathinfo( us($path_text) );
		return isset($info['extension']) ? $info['extension'] : '';
	}
}

return __FILE__;
