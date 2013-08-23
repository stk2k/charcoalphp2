<?php
/**
* DIコンポーネントやコンテナから参照可能なリソースの場所を管理するクラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ResourceLocator
{
	static $macro_defs;

	/*
	 * マクロ展開
	 */
	public static function processMacro( Charcoal_String $value )
	{
		// 展開キーワード
		if ( self::$macro_defs === NULL ){
			self::$macro_defs = array(

					'%APPLICATION_DIR%'  => CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/apps/' . CHARCOAL_APPLICATION,
					'%PROJECT_DIR%'      => CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT,
					'%WEBAPP_DIR%'       => CHARCOAL_WEBAPP_DIR,
					'%CHARCOAL_HOME%'    => CHARCOAL_HOME,
					'%BASE_DIR%'         => CHARCOAL_BASE_DIR,
					'%APPLICATION%'      => CHARCOAL_APPLICATION,

				);
		}

		// 展開
		$value = $value->getValue();
		if ( strpos($value,'%') !== FALSE ){
			foreach( self::$macro_defs as $macro_key => $macro_value ){
				if ( strpos($value,$macro_key) !== FALSE ){
					$value = str_replace( $macro_key, $macro_value, $value );
				}
			}
		}

		return s($value);
	}

	/**
	 * Get framework/project/application path
	 *
	 * @param Charcoal_String $virtual_path          virtual path to retrieve, including macro key like '%BASE_DIR%', '%WEBAPP_DIR%', etc.
	 * @param Charcoal_Object $value                 cache data to save
	 *
	 * @return Charcoal_String        full path string
	 */
	public function getPath( Charcoal_String $virtual_path, Charcoal_String $filename = NULL )
	{
		$path = self::processMacro( $virtual_path );

		if ( $filename ){
			$path .= '/' . $filename->getValue();
		}

		return $path;
	}

	/*
	 * フレームワーク以下のリソースのパスを取得
	 */
	public static function getFrameworkPath( Charcoal_String $folder = NULL, Charcoal_String $filename = NULL )
	{
		if ( !$folder ){
			return CHARCOAL_HOME;
		}

		$path = CHARCOAL_HOME . '/' . $folder->getValue();

		if ( $filename ){
			$path .= '/' . $filename->getValue();
		}

		return $path;
	}

	/*
	 * プロジェクト以下のリソースのパスを取得
	 */
	public static function getProjectPath( Charcoal_String $folder = NULL, Charcoal_String $filename = NULL )
	{
		if ( !$folder ){
			return CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT;
		}

		$path = CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/' . $folder->getValue();

		if ( $filename ){
			$path .= '/' . $filename->getValue();
		}

		return $path;
	}

	/*
	 * アプリケーション以下のリソースのパスを取得
	 */
	public static function getApplicationPath( Charcoal_String $folder = NULL, Charcoal_String $filename = NULL )
	{
		if ( !$folder ){
			return CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/apps/' . CHARCOAL_APPLICATION;
		}

		$path = CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/apps/' . CHARCOAL_APPLICATION . '/' . $folder->getValue();

		if ( $filename ){
			$path .= '/' . $filename->getValue();
		}

		return $path;
	}

	/*
	 * CHARCOAL_BASE_DIR以下のリソースのパスを取得
	 */
	public static function getBasePath( Charcoal_String $folder = NULL, Charcoal_String $filename = NULL )
	{
		if ( !$folder ){
			return CHARCOAL_BASE_DIR;
		}

		$path = CHARCOAL_BASE_DIR . '/' . $folder->getValue();

		if ( $filename ){
			$path .= '/' . $filename->getValue();
		}

		return $path;
	}

	/**
	 * Get framework/project/application file
	 *
	 * @param Charcoal_String $virtual_path          virtual path to retrieve, including macro key like '%BASE_DIR%', '%WEBAPP_DIR%', etc.
	 * @param Charcoal_Object $value                 cache data to save
	 *
	 * @return Charcoal_File        file object
	 */
	public function getFile( Charcoal_String $virtual_path, Charcoal_String $filename = NULL )
	{
		if ( $filename )
			return new Charcoal_File( s(self::getPath($virtual_path,$filename)) );
		else
			return new Charcoal_File( s(self::getPath($virtual_path)) );
	}

	/*
	 * フレームワークリソースのパスをFileインスタンスとして取得
	 */
	public static function getFrameworkFile( Charcoal_String $folder = NULL, Charcoal_String $filename = NULL )
	{
		if ( $filename )
			return new Charcoal_File( s(self::getFrameworkPath($folder,$filename)) );
		else if ( $folder )
			return new Charcoal_File( s(self::getFrameworkPath($folder)) );
		else
			return new Charcoal_File( s(self::getFrameworkPath()) );
	}

	/*
	 * プロジェクトリソースのパスをFileインスタンスとして取得
	 */
	public static function getProjectFile( Charcoal_String $folder = NULL, Charcoal_String $filename = NULL )
	{
		if ( $filename )
			return new Charcoal_File( s(self::getProjectPath($folder,$filename)) );
		else if ( $folder )
			return new Charcoal_File( s(self::getProjectPath($folder)) );
		else
			return new Charcoal_File( s(self::getProjectPath()) );
	}

	/*
	 * web_app以下のリソースのパスをFileインスタンスとして取得
	 */
	public static function getApplicationFile( Charcoal_String $folder = NULL, Charcoal_String $filename = NULL )
	{
		if ( $filename )
			return new Charcoal_File( s(self::getApplicationPath($folder,$filename)) );
		else if ( $folder )
			return new Charcoal_File( s(self::getApplicationPath($folder)) );
		else
			return new Charcoal_File( s(self::getApplicationPath()) );
	}


}
return __FILE__;
