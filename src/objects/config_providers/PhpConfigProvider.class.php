<?php
/**
*
* PHP配列によるデフォルトの設定プロバイダ
*
* PHP version 5
*
* @package    config_providers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_PhpConfigProvider extends Charcoal_AbstractConfigProvider
{

	/*
	 *    コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 設定プロバイダ名
	 *
	 * @access    public
	 * @return    String
	 */
	public function getProviderName()
	{
		return "Default Config Provider";
	}

	/**
	 *　設定をロード
	 *
	 * @access    public
	 * @param     config_name 設定名
	 */
	public function load( Charcoal_String $config_root, Charcoal_String $config_name )
	{
		$config_root = us( $config_root );
		$config_name = us( $config_name );

		log_info( "config",  "設定ファイル[$config_name]を読み込みます。" );

		$config_file = $config_root . '/' . "$config_name.php";

//print "config_file:$config_file<BR>";
		if ( !is_file($config_file) ){			
			log_info( "config",  "設定ファイル[$config_file]は存在しません。" );
			return NULL;
		}

		// 設定スクリプト内での出力はすべて破棄する
//		ob_start();

		include ( $config_file );

//		ob_end_clean();
	
//print System::arrayToString($config) . "<BR>";

		log_info( "config",  "設定ファイル[$config_name]を読み込みました。" );

		return $config;
	}

}

