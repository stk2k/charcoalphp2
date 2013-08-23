<?php
/**
* 設定ファイルの妥当性を検証するクラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ConfigValidator extends Charcoal_Object
{
	private $_config;

	/*
	 *	コンストラクタ
	 */
	public function __construct( Charcoal_Config $config )
	{
		parent::__construct();

		$this->_config = $config;
	}

	/*
	 *	バリデータをロードする
	 */
	public static function loadValidator( Charcoal_String $validator_name )
	{
		// 設定プロバイダを作成
		$provider = Charcoal_Factory::createConfigProvider();

		// 設定ルートパス
		$root_framework = Charcoal_ResourceLocator::getFrameworkPath();

		// 設定ファイルを読み込む
		$config_name = '/config_validators/' . $validator_name->getValue() . '.config_validator';

		$config = new Charcoal_Config();
		$provider->loadConfigByName( s($root_framework . '/config'), s($config_name), $config );

		return new Charcoal_ConfigValidator( $config );
	}

	/**
	 *	妥当性を検証する
	 */
	public function validate( Charcoal_Config $config )
	{
	}

}

return __FILE__;
