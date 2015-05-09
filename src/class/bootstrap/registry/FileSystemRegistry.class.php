<?php
/**
* registry implemeted by file system
*
* PHP version 5
*
* @package    class.bootstrap.registry
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_FileSystemRegistry extends Charcoal_AbstractRegistry
{
	private $sandbox;

	/**
	 *  Constructor
	 */
	public function __construct( $sandbox )
	{
//		Charcoal_ParamTrait::validateSandbox( 1, $sandbox );

		$this->sandbox = $sandbox;

		parent::__construct();
	}

	/**
	 * get configuration data by key
	 * 
	 * @param string[] $keys           key list
	 * @param string $obj_path         object path
	 * @param string $type_name        type name of the object
	 *
	 * @return mixed              configuration data
	 */
	public function get( array $keys, $obj_path, $type_name )
	{
//		Charcoal_ParamTrait::validateString( 1, $key );

		// get config povier
		$provider = $this->sandbox->getConfigProvider();

		// read cache data
		$base_dir = 'config/' . CHARCOAL_PROJECT . '/' . CHARCOAL_APPLICATION . '/' . $type_name;
		$base_dir = empty($obj_path->getRealPath()) ? $base_dir : $base_dir . '/' . $obj_path->getRealPath();
		$cache_file = $obj_path->getObjectName() . '.config.php';
		$cache_file_path = CHARCOAL_CACHE_DIR . '/' . $base_dir . '/' . $cache_file;
		$cached_config = is_file($cache_file_path) && is_readable($cache_file_path) ? require( $cache_file_path ) : null;

		if ( is_array($cached_config) )
		{
			// read cache created date
			$cache_date = isset($cached_config['cache_date']) ? $cached_config['cache_date'] : false;

			// get each config
			$config_all = array();
			foreach( $keys as $key )
			{
				// cache entry
				$cache_exists = isset($cached_config['config'][$key]);

				// read config file date
				$config_date = $provider->getConfigDate( $key );

				// if cache data exists and config file does not exist
				if ( $cache_exists && $config_date === false ){
					goto LOAD_CONFIG_FROM_FILE;
				}

				// if config file 
				if ( $config_date !== false && $config_date > $cache_date ){
					goto LOAD_CONFIG_FROM_FILE;
				}

				// correct cached data
				$cache_data = $cached_config['config'][$key];
				$config_all = $cache_data ? array_merge( $config_all, $cache_data ) : $config_all;
			}

			// if cache is not modified, return cache data
			return $config_all;
		}

LOAD_CONFIG_FROM_FILE:
		// get all config data from file
		$config_all = array();
		$config_by_key = array();
		foreach( $keys as $key )
		{
			$config = $provider->loadConfig( $key );
			if ( is_array($config) ){
				$config_all = array_merge( $config_all, $config );
				$config_by_key[$key] = $config;
			}
			else{
				$config_by_key[$key] = array();
			}
		}

		// create cache directory recursively
		$dirs = explode('/',$base_dir);
		$dir_walk = '';
		foreach( $dirs as $d ){
			if ( empty($d) )	continue;
			$dir_walk .= "/$d";
			//echo "dir_walk: $dir_walk" . PHP_EOL;
			$checkdir = CHARCOAL_CACHE_DIR . $dir_walk;
			//echo "checkdir: $checkdir" . PHP_EOL;
			if ( !file_exists($checkdir) ){
				mkdir($checkdir);
				//echo "mkdir: $checkdir" . PHP_EOL;
			}
		}

		// make new cache data
		$new_cache = array(
				'cache_date' => time(),
				'config' => $config_by_key,
			);
		$lines = "<?php return " . var_export($new_cache, true) . ";";

		// create cache file
		file_put_contents($cache_file_path, $lines, LOCK_EX);

		return $config_all;
	}

}

