<?php
/**
*
* YAMLパーサ（Spyc）による設定（キャッシュ機能付き）
*
* PHP version 5
*
* @package    config_providers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
require_once('Cache/Lite.php');

class Charcoal_CachedSpycConfigProvider extends Charcoal_Object implements Charcoal_IConfigProvider
{
	var $_cache;
	var $_config_file;

	/**
	 *    コンストラクタ
	 *
	 * @access    public
	 */
	public function __construct()
	{
		parent::__construct();

		// キャッシュディレクトリ
		$cache_dir = ResourceLocator::getPath( ResourcePath::TEMP );

		// キャッシュオプション
		$options = array(
			'cacheDir' => "$cache_dir/",
			'lifeTime' => 999999999,
			'automaticSerialization' => TRUE
		);
		
		// キャッシュオブジェクト
		$this->_cache = new Cache_Lite($options);
	}

	/**
	 * 設定プロバイダ名
	 *
	 * @access    public
	 * @return    String
	 */
	public function getProviderName()
	{
		return "Cached Spyc Config Provider";
	}

	/**
	 *　設定をロード
	 *
	 * @access    public
	 * @param     config_root 設定ルート
	 * @param     config_name 設定名
	 */
	public function load( Charcoal_String $config_root, Charcoal_String $config_name )
	{
		$config_name = us( $config_name );

		$config_file = ResourceLocator::getPath( ResourcePath::CONFIG, "$config_name.yml" );

		log_info( "system",  "設定ファイル[$config_file]を読み込みます。" );

		// キャッシュがあるか
		$cache_data = $this->_cache->get($config_file);
		if ( $cache_data )
		{
			// あればそれを設定
			$config = $cache_data;
		}
		else{
			require_once('spyc.php');

			if ( is_file($config_file) )
			{
				// 設定のロード
				$config = Spyc::YAMLLoad($config_file);

				// キャッシュに保存
				$ret = $this->_cache->save( $config, $config_file );
			}
		}

		log_info( "system",  "設定ファイル[$config_file]を読み込みました。" );

		return $config;
	}

}

return __FILE__;