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
	public static function processMacro( $value )
	{
//		Charcoal_ParamTrait::checkString( 1, $value );

		// 展開キーワード
		if ( self::$macro_defs === NULL ){
			self::$macro_defs = array(

					'%APPLICATION_DIR%'          => CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/apps/' . CHARCOAL_APPLICATION,
					'%APPLICATION_CLASSES_DIR%'  => CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/apps/' . CHARCOAL_APPLICATION . '/classes',
					'%PROJECT_DIR%'              => CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT,
					'%PROJECT_CLASSES_DIR%'      => CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/classes',
					'%WEBAPP_DIR%'               => CHARCOAL_WEBAPP_DIR,
					'%CHARCOAL_HOME%'            => CHARCOAL_HOME,
					'%APPLICATION%'              => CHARCOAL_APPLICATION,

				);
		}

		// 展開
		$value = us( $value );
		if ( strpos($value,'%') !== FALSE ){
			foreach( self::$macro_defs as $macro_key => $macro_value ){
				if ( strpos($value,$macro_key) !== FALSE ){
					$value = str_replace( $macro_key, $macro_value, $value );
				}
			}
		}

		return $value;
	}

	/**
	 * Get framework/project/application path
	 *
	 * @param string $virtual_path          virtual path to retrieve, including macro key like '%BASE_DIR%', '%WEBAPP_DIR%', etc.
	 * @param string $filename              file name
	 *
	 * @return string        full path string
	 */
	public function getPath( $virtual_path, $filename = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $virtual_path );
//		Charcoal_ParamTrait::checkString( 2, $filename, TRUE );

		$path = self::processMacro( $virtual_path );

		if ( $filename ){
			$path .= '/' . $filename;
		}

		return $path;
	}

	/*
	 * フレームワーク以下のリソースのパスを取得
	 */
	public static function getFrameworkPath( $folder = NULL, $filename = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $folder );
//		Charcoal_ParamTrait::checkString( 2, $filename, TRUE );

		if ( !$folder ){
			return CHARCOAL_HOME;
		}

		$path = CHARCOAL_HOME . '/' . $folder;

		if ( $filename ){
			$path .= '/' . $filename;
		}

		return $path;
	}

	/*
	 * プロジェクト以下のリソースのパスを取得
	 */
	public static function getProjectPath( $folder = NULL, $filename = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $folder );
//		Charcoal_ParamTrait::checkString( 2, $filename, TRUE );

		if ( !$folder ){
			return CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT;
		}

		$path = CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/' . $folder;

		if ( $filename ){
			$path .= '/' . $filename;
		}

		return $path;
	}

	/*
	 * アプリケーション以下のリソースのパスを取得
	 */
	public static function getApplicationPath( $folder = NULL, $filename = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $folder );
//		Charcoal_ParamTrait::checkString( 2, $filename, TRUE );

		if ( !$folder ){
			return CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/apps/' . CHARCOAL_APPLICATION;
		}

		$path = CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/apps/' . CHARCOAL_APPLICATION . '/' . $folder;

		if ( $filename ){
			$path .= '/' . $filename;
		}

		return $path;
	}

	/**
	 * Get framework/project/application file
	 *
	 * @param Charcoal_String $folder          virtual path to retrieve, including macro key like '%BASE_DIR%', '%WEBAPP_DIR%', etc.
	 * @param Charcoal_Object $value                 cache data to save
	 *
	 * @return Charcoal_File        file object
	 */
	public function getFile( $folder, $filename = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $virtual_path );
//		Charcoal_ParamTrait::checkString( 2, $filename, TRUE );

		return new Charcoal_File( self::getPath( $folder, $filename ) );
	}

	/*
	 * フレームワークリソースのパスをFileインスタンスとして取得
	 */
	public static function getFrameworkFile( $folder = NULL, $filename = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $folder, TRUE );
//		Charcoal_ParamTrait::checkString( 2, $filename, TRUE );

		return new Charcoal_File( self::getFrameworkPath( $folder, $filename ) );
	}

	/*
	 * プロジェクトリソースのパスをFileインスタンスとして取得
	 */
	public static function getProjectFile( $folder = NULL, $filename = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $folder, TRUE );
//		Charcoal_ParamTrait::checkString( 2, $filename, TRUE );

		return new Charcoal_File( self::getProjectPath( $folder, $filename ) );
	}

	/*
	 * web_app以下のリソースのパスをFileインスタンスとして取得
	 */
	public static function getApplicationFile( $folder = NULL, $filename = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $folder, TRUE );
//		Charcoal_ParamTrait::checkString( 2, $filename, TRUE );

		return new Charcoal_File( self::getApplicationPath( $folder, $filename ) );
	}


}

