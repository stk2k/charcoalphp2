<?php
/**
*
* YAMLパーサ（Spyc）による設定
*
* PHP version 5
*
* @package    config_providers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_SpycConfigProvider extends Charcoal_Object implements Charcoal_IConfigProvider
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
		return "Spyc Config Provider";
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

		$config_file = $config_root . '/' . "$config_name.yml";

		if ( !is_file($config_file) ){			
			log_info( "config",  "設定ファイル[$config_file]は存在しません。" );
			return NULL;
		}

		require_once ( 'spyc.php' );

		// 設定のロード
	    $Spyc = new Spyc;
	    $config = $Spyc->load($config_file);

//print "config_file:$config_file<BR>";
//print System::arrayToString($config) . "<BR>";
	
		log_info( "config",  "設定ファイル[$config_name]を読み込みました。" );

		return $config;
	}

}

