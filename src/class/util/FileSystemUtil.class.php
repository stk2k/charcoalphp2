<?php
/**
* ファイルシステム操作に関するユーティリティクラス
*
* PHP version 5
*
* @package    class.util
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
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
			_throw( new Charcoal_FileSystemException( 'copy', $src->getAbsolutePath() . ' is not file' ) );
		}
		if ( !$src->isReadable() ){
			_throw( new Charcoal_FileSystemException( 'copy', $src->getAbsolutePath() . ' is not readable' ) );
		}

		// コピー先の確認
		$dir = $dest->getDir();
		if ( !$dir->isDir() ){
			_throw( new Charcoal_FileSystemException( 'copy', $dir->getAbsolutePath() . ' is not directory' ) );
		}

		$src = us( $src->getPath() );
		$dest = us( $dest->getPath() );

		$result = copy( $src, $dest );

		if ( false === $result ){
			_throw( new Charcoal_FileSystemException( 'copy', "[src]$src [dest]$dest" ) );
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

	/**
	 * Output file
	 * 
	 * @param Charcoal_File $file       path to output
	 * @param array $lines              each line of the file to output
	 */
	public static function outputFile( Charcoal_File $file, array $lines )
	{
		$file_name = $file->getPath();
		$fp = fopen($file_name,"w");
		if ( $fp === FALSE ){
			_throw( new Charcoal_FileOpenException($file) );
		}
		foreach( $lines as $line ){
			$res = fwrite( $fp, $line . PHP_EOL );
			if ( $res === FALSE ){
				_throw( new Charcoal_FileOutputException($file) );
			}
		}
		fclose($fp);
	}
}


