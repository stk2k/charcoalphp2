<?php
/**
* 設定を定義するインターフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

interface Charcoal_IConfigProvider
{
	/*
	 *　設定プロバイダにオプションをセット
	 */
	public function setOptions( Charcoal_Properties $options );

	/*
	 *　設定を名前でロード
	 */
	public function loadConfigByName( 
						Charcoal_String $config_root, 
						Charcoal_String $config_name, 
						Charcoal_Config& $config
					);

}

