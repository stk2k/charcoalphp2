<?php
/**
*
* ユーザ定義クラスローダの基底クラス
*
* PHP version 5
*
* @package    objects.class_loaders
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
	public function configure( $config )
	{
		parent::configure( $config );

	}

	/*
	 * クラスとパスの対応を表す連想配列を取得
	 */
	public abstract function getClassPathAssoc( $class_name );

	/*
	 * クラス名からクラスパスを取得
	 */
	public function getClassPath( $class_name )
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
	public function loadClass( $class_name )
	{
		$class_path = $this->getClassPath( $class_name );

		$is_debug = $this->getSandbox()->isDebug();

		if ( $is_debug ) log_debug( "system,debug,class_loader", "class_path=$class_path", "class_loader" );

		if ( $class_path === FALSE || !is_string($class_path) ){
			return FALSE;
		}
		$class_path = trim($class_path,PATH_SEPARATOR);

		$file_name = $class_name . CHARCOAL_CLASS_FILE_SUFFIX;
		$pos = strpos( $file_name, CHARCOAL_CLASS_PREFIX );
		if ( $pos !== FALSE ){
			$file_name = substr( $file_name, $pos + strlen(CHARCOAL_CLASS_PREFIX) );
		}
		if ( $is_debug ) log_debug( "system,debug,class_loader", "file_name=$file_name", "class_loader" );

		// プロジェクトディレクトリ配下のクラスファイルをロード
		$file_path = CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/' . $class_path . '/' . $file_name;

		if ( is_file($file_path) ){
			include( $file_path );
			if ( $is_debug ) log_debug( "system,debug,include,class_loader", "class file loaded: [$file_path]", "class_loader" );
			return TRUE;
		}

		if ( $is_debug ) log_debug( "system,debug,include,class_loader", "file not found: [$file_path]", "class_loader" );

		// アプリケーションディレクトリ配下のクラスファイルをロード
		$file_path = CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/app/' . CHARCOAL_APPLICATION . '/' . $class_path . '/' . $file_name;

		if ( is_file($file_path) ){
			include( $file_path );
			if ( $is_debug ) log_debug( "system,debug,include,class_loader", "class file loaded: [$file_path]", "class_loader");
			return TRUE;
		}

		if ( $is_debug ) log_debug( "system,debug,include,class_loader", "file not found: [$file_path]", "class_loader" );
		if ( $is_debug ) log_debug( "system,debug,class_loader", "class not found: [$class_name]", "class_loader" );

		return FALSE;
	}
}

