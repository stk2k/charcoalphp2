<?php
/**
*
* ユーザ定義クラスローダの基底クラス
*
* PHP version 5
*
* @package    config_providers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
abstract class Charcoal_UserClassLoader extends Charcoal_CharcoalObject implements Charcoal_IClassLoader
{
	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
	}

	/*
	 * クラスとパスの対応を表す連想配列を取得
	 */
	public abstract function getClassPathAssoc( Charcoal_String $class_name );

	/*
	 * クラス名からクラスパスを取得
	 */
	public function getClassPath( Charcoal_String $class_name )
	{
		$assoc = $this->getClassPathAssoc( $class_name );

		if ( $assoc === NULL || $assoc === FALSE || !is_array($assoc) ){
			return FALSE;
		}

		$class_name = us($class_name);
		$class_path = isset($assoc[$class_name]) ? $assoc[$class_name] : FALSE;
		return $class_path;
	}

	/*
	 * クラスをロード
	 */
	public function loadClass( Charcoal_String $class_name )
	{
		$class_path = $this->getClassPath( $class_name );
//		log_debug( "class_loader", "class_loader", "class_path=$class_path" );

		if ( $class_path === FALSE || !is_string($class_path) ){
			return FALSE;
		}
		$class_path = trim($class_path,PATH_SEPARATOR);
//		log_debug( "class_loader", "class_loader", "class_path=$class_path" );

		$file_name = $class_name . CHARCOAL_CLASS_FILE_SUFFIX;
		$pos = strpos( $file_name, CHARCOAL_CLASS_PREFIX );
		if ( $pos !== FALSE ){
			$file_name = substr( $file_name, $pos+1 );
		}
//		log_debug( "class_loader", "class_loader", "file_name=$file_name" );

		// プロジェクトディレクトリ配下のクラスファイルをロード
		$file_path = CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/' . $class_path . '/' . $file_name;

		if ( is_readable($file_path) ){
			require_once( $file_path );
//			log_info( "system,debug,include,class_loader", "class_loader", "[" . get_class($this) . "] class file loaded: class=[$class_name] file=[$file_path]" );
			return TRUE;
		}

		// アプリケーションディレクトリ配下のクラスファイルをロード
		$file_path = CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/apps/' . CHARCOAL_APPLICATION . '/' . $class_path . '/' . $file_name;

		if ( is_readable($file_path) ){
			require_once( $file_path );
//			log_info( "system,debug,include,class_loader", "class_loader", "[" . get_class($this) . "] class file loaded: class=[$class_name] file=[$file_path]" );
			return TRUE;
		}

//		log_info( "system,debug,class_loader", "class_loader", "class not found: [" . get_class($this) . "]" );

		return FALSE;
	}
}
return __FILE__;
