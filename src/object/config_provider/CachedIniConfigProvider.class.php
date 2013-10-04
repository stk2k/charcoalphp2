<?php
/**
*
* config provider implementation of .ini file(parse_ini_file)
*
* PHP version 5
*
* @package    objects.config_providers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_CachedIniConfigProvider extends Charcoal_IniConfigProvider
{
	/**
	 *  load config
	 *
	 * @param  Charcoal_String $config_root   root name
	 * @param  Charcoal_String $config_name   config name
	 *
	 * @return mixed   configure data
	 */
	public function loadConfig( Charcoal_String $config_root, Charcoal_String $config_name )
	{
		$cache_key = $config_root->append($config_name);
		$cache_key = sha1( $cache_key );

		$source = new Charcoal_File( s("$cache_key.ini") );

		$cache = $this->getCache( s($cache_key), $source );

		if ( $cache ){
			if ( is_array($cache) ){
				return $cache;
			}
		}

		$data = parent::loadConfig( $config_root, $config_name );

		if ( is_array($data) ){
			$this->setCache( s($cache_key), $data );
		}

		return $data;
	}

	/**
	 *  get cache
	 *
	 * @param  Charcoal_String $cache_key      identify cache
	 * @param  Charcoal_File $source           config file
	 *
	 * @return mixed   configure data
	 */
	public function getCache( Charcoal_String $cache_key, Charcoal_File $source )
	{
		$cache_dir = Charcoal_Profile::getString( s('CACHE_DIR') );
		$cache_dir = new Charcoal_File( s($cache_dir) );

		$cache_file = new Charcoal_File( s($cache_key), $cache_dir );

		if ( !$cache_file->isFile() )	return FALSE;

		if ( $source->isFile() ){
			$lm_cache = $cache_file->getLastModified();
			$lm_source = $source->getLastModified();

			if ( $lm_cache === FALSE || $lm_source === FALSE )	return FALSE;

			if ( $lm_cache >= $lm_source )	return FALSE;
		}

		return unserialize( $cache_file->getContents() );
	}

	/**
	 *  set cache
	 *
	 * @param  Charcoal_String $cache_key      identify cache
	 * @param  array $data                     config data
	 */
	public function setCache( Charcoal_String $cache_key, array $data )
	{
		$cache_dir = Charcoal_Profile::getString( s('CACHE_DIR') );
		$cache_dir = new Charcoal_File( s($cache_dir) );

		$cache_dir_mode = Charcoal_Profile::getString( s('CACHE_DIR_MODE'), s('777') );
		$cache_dir->makeDirectory( $cache_dir_mode, b(TRUE) );

		if ( !$cache_dir->isDir() ){
			return;
		}

		$cache_file = new Charcoal_File( s($cache_key), $cache_dir );

		$contents = serialize( $data );

		$fp = fopen( $cache_file, 'wb' );
		if ( $fp ){
			if ( flock($fp, LOCK_EX) ){
				fwrite( $fp, $contents );
				flock($fp, LOCK_UN);
			}
			$ret = fclose($fp);
		}
	}


}

