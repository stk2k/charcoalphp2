<?php
/**
* DIコンポーネントやコンテナから参照可能なリソースの場所を管理するクラス
*
* PHP version 5
*
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ResourceLocator
{
	static $static_macro_defs;

	/*
	 * マクロ展開
	 */
	public static function processMacro( $env, $value )
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_IEnvironment', $env );

		if ( is_array($value) || $value instanceof Iterator ){
			$new_array = array();
			foreach( $value as $key => $value ){
				$key = is_string($key) ? self::processMacro( $env, $key ) : $key;
				$value = is_string($value) ? self::processMacro( $env, $value ) : $value;
				$new_array[$key] = $value;
			}
			return $new_array;
		}

		$value = us( $value );

		if ( !is_string($value) ){
			return $value;
		}

		if ( strpos($value,'%') === FALSE ){
			return $value;
		}

		if ( !self::$static_macro_defs ){
			self::$static_macro_defs = array(
				'%APPLICATION_DIR%'  => CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/app/' . CHARCOAL_APPLICATION,
				'%PROJECT_DIR%'      => CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT,
				'%WEBAPP_DIR%'       => CHARCOAL_WEBAPP_DIR,
				'%CHARCOAL_HOME%'    => CHARCOAL_HOME,
				'%PROJECT%'          => CHARCOAL_PROJECT,
				'%APPLICATION%'      => CHARCOAL_APPLICATION,
				'%BASE_DIR%'         => CHARCOAL_BASE_DIR,
				'%CACHE_DIR%'        => CHARCOAL_CACHE_DIR,
				'%TMP_DIR%'          => CHARCOAL_TMP_DIR,
			);
		}

		// fill static values
		foreach( self::$static_macro_defs as $macro_key => $macro_value ){
			if ( strpos($value,$macro_key) !== FALSE ){
				$value = str_replace( $macro_key, $macro_value, $value );
			}
		}

		// set runtime values
		$request_path = $env->get( '%REQUEST_PATH%' );
		$runtime_macro_defs = array(
				'%PROC_PATH_REAL%' => str_replace( ':', '/', substr($request_path,2) ),
			);

		// fill runtime values
		foreach( $runtime_macro_defs as $macro_key => $macro_value ){
			if ( strpos($value,$macro_key) !== FALSE ){
				$value = str_replace( $macro_key, $macro_value, $value );
			}
		}

		return $value;
	}

	/**
	 * Get framework/project/application path
	 *
	 * @param Charcoal_IEnvironment $env    framework's environment variables
	 * @param string $virtual_path          virtual path to retrieve, including macro key like '%BASE_DIR%', '%WEBAPP_DIR%', etc.
	 * @param string $filename              file name
	 *
	 * @return string        full path string
	 */
	public static function getPath( $env, $virtual_path, $filename = NULL )
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_IEnvironment', $env );
		Charcoal_ParamTrait::checkString( 2, $virtual_path );
		Charcoal_ParamTrait::checkString( 3, $filename, TRUE );

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
			return CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/app/' . CHARCOAL_APPLICATION;
		}

		$path = CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/app/' . CHARCOAL_APPLICATION . '/' . $folder;

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
	public static function getFile( $folder, $filename = NULL )
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

