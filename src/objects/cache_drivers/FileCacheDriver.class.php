<?php
/**
*
* cache driver for memcached
*
* PHP version 5
*
* @package    config_providers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_FileCacheDriver extends Charcoal_CharcoalObject implements Charcoal_ICacheDriver
{
	const CACHE_FILE_EXT_META               = 'meta';
	const CACHE_FILE_EXT_DATA               = 'data';

	private $_cache_root;
	private $_cache_root_dir;
	private $_default_duration;

	/*
	 *	Construct object
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
		parent::configure( $config );

		$default_cache_root = Charcoal_ResourceLocator::getApplicationPath( s('cache') );

		$this->_cache_root        = $config->getString( s('cache_root'), s($default_cache_root) );
		$this->_default_duration  = $config->getInteger( s('default_duration'), i(0) );

		$this->_cache_root_dir = new Charcoal_File( $this->_cache_root );

		log_debug( "system, debug, cache", "cache_root=[{$this->_cache_root}]" );
		log_debug( "system, debug, cache", "default_duration=[{$this->_default_duration}]" );
	}

	/**
	 * Get cache data file name
	 */
	public function getCacheDataFileName( Charcoal_String $key )
	{
		return s( $key. '.' . self::CACHE_FILE_EXT_DATA );
	}

	/**
	 * Get cache meta file name
	 */
	public function getCacheMetaFileName( Charcoal_String $key )
	{
		return s( $key. '.' . self::CACHE_FILE_EXT_META );
	}

	/**
	 * Get cache data file object
	 */
	public function getCacheDataFile( Charcoal_String $key )
	{
		return new Charcoal_File( $this->getCacheDataFileName($key), $this->_cache_root_dir );
	}

	/**
	 * Get cache meta file object
	 */
	public function getCacheMetaFile( Charcoal_String $key )
	{
		return new Charcoal_File( $this->getCacheMetaFileName($key), $this->_cache_root_dir );
	}

	/**
	 * Get non-typed data which is associated with a string key
	 *
	 * @param Charcoal_String $key         The key of the item to retrieve.
	 */
	public function get( Charcoal_String $key )
	{
		$meta_file = $this->getCacheMetaFile($key);
		$data_file = $this->getCacheDataFile($key);

		// read cache meta file
//		if ( !$meta_file->exists() || !$meta_file->canRead() ){
		if ( !$meta_file->exists() ){
			log_debug("system,debug,cache", "cache", "Can not read meta file[$meta_file]" );
			return FALSE;
		}

		$meta_data = $this->_readMeta( $meta_file );
		if ( $meta_data === FALSE ){
			log_debug("system,debug,cache"," cache", "Can not read meta file[$meta_file]" );
			return FALSE;
		}

		// read cache data file
		if ( !$data_file->exists() || !$data_file->canRead() ){
			log_debug("system,debug,cache"," cache", "Can not read data file[$data_file]" );
			return FALSE;
		}

		$serialized_data = $data_file->getContents();

		// check SHA1 digest
		if ( !isset($meta_data['sha1_digest']) ){
			log_warning("system,debug,cache", "cache", "Not found mandatory field[sha1_digest] in meta file[$meta_file]" );
			return FALSE;
		}

		$sha1_digest1 = sha1($serialized_data);
		$sha1_digest2 = $meta_data['sha1_digest'];
		if ( $sha1_digest1 != $sha1_digest2 ){
			log_warning("system,debug,cache", "cache", "Data file SHA1 digest does not match the value in meta file[$meta_file] digest1=[$sha1_digest1] digest2=[$sha1_digest2]" );
			return FALSE;
		}

		log_info("system,debug,cache", "cache", "cache[$key] HIT!" );

		return unserialize($serialized_data);
	}

	/**
	 * Save a value to cache
	 *
	 * @param Charcoal_String $key         The key under which to store the value.
	 * @param Charcoal_Integer $duration   specify expiration span which the cache will be removed.
	 */
	public function set( Charcoal_String $key, Charcoal_Object $value, Charcoal_Integer $duration = NULL )
	{
		$meta_file = $this->getCacheMetaFile($key);
		$data_file = $this->getCacheDataFile($key);

		$duration = $duration ? ui($duration) : ui($this->_default_duration);

		$serialized_data = serialize($value);
		$sha1_digest = sha1($serialized_data);

		// save meta file
		$meta_data['expire_date'] = ($duration == 0) ? 0 : date('Y-m-d H:i:s', strtotime("+{$duration} seconds"));
		$meta_data['sha1_digest'] = $sha1_digest;

		$this->_writeMeta( $meta_file, p($meta_data) );

		// save data file
		$res = $data_file->putContents( s($serialized_data) );
		if ( $res === FALSE ){
			_throw( new Charcoal_CacheDriverException( 'file', "cache set failed. Saving data file failed: [$data_file]" ) );
		}
	}

	/**
	 * Read meta data
	 *
	 */
	private function _readMeta( Charcoal_File $meta_file )
	{
		$meta_data = parse_ini_file($meta_file->getPath());

		if ( $meta_data === FALSE || !is_array($meta_data) ){
			log_debug("system,debug,cache", "cache", "Can not parse meta file[$meta_file]" );
			return FALSE;
		}

		// check expire date
		if ( !isset($meta_data['expire_date']) ){
			log_warning("system,debug,cache", "cache", "Not found mandatory field[expire_date] in meta file[$meta_file]" );
			return FALSE;
		}

		$expire_date = $meta_data['expire_date'];
		$expire_date = ($expire_date == 0) ? 0 : strtotime($expire_date);

		if ( $expire_date === FALSE ){
			log_warning("system,debug,cache", "cache", "field[expire_date] must be date value or zero in meta file[$meta_file]" );
			return FALSE;
		}

		if ( $expire_date > 0 && $expire_date < time() ){
			log_debug("system,debug,cache", "cache", "Cache expired: [$meta_file]" );
			return FALSE;
		}

		return $meta_data;
	}

	/**
	 * Correct meta data
	 *
	 */
	private function _buildMeta( Charcoal_String $key )
	{
		$meta = array();

		// read data file to retrieve sha1 digest
		$data_file = $this->getCacheDataFile($key);

		if ( !$data_file->exists() || !$data_file->canRead() ){
			log_debug("system,debug,cache"," cache", "Can not read data file[$data_file]" );
			return FALSE;
		}

		$sha1_digest = sha1($data_file->getContents());
		$meta['sha1_digest'] = $sha1_digest;

		return $meta;
	}

	/**
	 * Save meta data
	 *
	 */
	private function _writeMeta( Charcoal_File $meta_file, Charcoal_Properties $meta_data )
	{
		$meta_data = up($meta_data);

		$fp = @fopen( $meta_file->getPath(), 'w' );
		if ( $fp === FALSE ){
			_throw( new Charcoal_CacheDriverException( 'file', "cache set failed. Saving meta file failed: [$meta_file]" ) );
		}

		foreach( $meta_data as $meta_key => $meta_value ){
			fwrite( $fp, "{$meta_key} = {$meta_value}\n" );
		}

		fclose( $fp );
	
	}

	/**
	 * Remove a cache data
	 *
	 * @param Charcoal_String $key         The key of the item to remove. Shell wildcards are accepted.
	 * @param Charcoal_Boolean $regEx      specify regular expression in $key parameter, default is NULL which means FALSE.
	 */
	public function delete( Charcoal_String $key, Charcoal_Boolean $regEx = NULL )
	{
		// apply wildcard file filter
		$data_filter = new Charcoal_WildcardFileFilter($this->getCacheDataFileName($key));
		$meta_filter = new Charcoal_WildcardFileFilter($this->getCacheMetaFileName($key));

		$filter = new Charcoal_CombinedFileFilter( v(array($data_filter,$meta_filter)) );

		$this->_delete( $filter );
	}

	/**
	 * Remove a cache data searched by regular expression
	 *
	 * @param Charcoal_String $key         The key of the item to remove. Regular expression are accepted.
	 * @param Charcoal_Boolean $regEx      specify regular expression in $key parameter, default is NULL which means FALSE.
	 */
	public function deleteRegEx( Charcoal_String $key )
	{
		// apply wildcard file filter
		$data_filter = new Charcoal_RegExFileFilter( $key, s(self::CACHE_FILE_EXT_DATA) );
		$meta_filter = new Charcoal_RegExFileFilter( $key, s(self::CACHE_FILE_EXT_META) );

		$filter = new Charcoal_CombinedFileFilter( v(array($data_filter,$meta_filter)) );

		$this->_delete( $filter );
	}

	/**
	 * Delete internal
	 *
	 */
	public function _delete( Charcoal_IFileFilter $filter )
	{
		// select files and delete them all
		$files = $this->_cache_root_dir->listFiles( $filter );
		if ( $files && is_array($files) ){
			foreach( $files as $file ){
				if ( $file->exists() && $file->isFile() ){
					$file->delete();
				}
			}
		}
	}

	/**
	 * Rewrite cache expiration time
	 *
	 * @param Charcoal_String $key         The key of the item to remove. Shell wildcards are accepted.
	 * @param Charcoal_Integer $duration   specify expiration span which the cache will be removed.
	 */
	public function touch( Charcoal_String $key, Charcoal_Integer $duration = NULL )
	{
		$duration = $duration ? ui($duration) : ui($this->_default_duration);

		// apply wildcard file filter
		$filter = new Charcoal_WildcardFileFilter($this->getCacheMetaFileName($key));

		$this->_touch( $filter, $duration );
	}

	/**
	 * Rewrite cache expiration time searched by regular expression
	 *
	 * @param Charcoal_String $key         The key of the item to remove. Regular expression are accepted.
	 * @param Charcoal_Integer $duration   specify expiration span which the cache will be removed.
	 */
	public function touchRegEx( Charcoal_String $key, Charcoal_Integer $duration = NULL )
	{
		$duration = $duration ? ui($duration) : ui($this->_default_duration);

		// apply wildcard file filter
		$filter = new Charcoal_RegExFileFilter( $key, s(self::CACHE_FILE_EXT_META) );

		$this->_touch( $filter, $duration );
	}


	/**
	 * Touch internal
	 *
	 */
	public function _touch( Charcoal_IFileFilter $filter, Charcoal_Integer $duration )
	{
		$expire_date = date( 'Y-m-d H:i:s', strtotime("+{$duration} seconds") );

		// meta suffix
		$suffix = '.' . CACHE_FILE_EXT_META;

		// select files and delete them all
		$files = $this->_cache_root_dir->listFiles( $meta_filter );
		if ( $files && is_array($files) ){
			foreach( $files as $file ){
				$key = basename( $file, $suffix );

				// read cache meta file
				$meta = $this->_readMeta( $meta_file );
				if ( $meta === FALSE ){
					$meta = $this->_buildMeta( $key );
					if ( $meta === FALSE ){
						continue;
					}
				}

				// save meta file
				$meta['expire_date'] = $expire_date;

				$this->_writeMeta( $meta_file, p($meta) );
			}
		}
	}

}

