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
     *
     * @param Charcoal_Sandbox $sandbox
     */
    public function __construct( $sandbox )
    {
//        Charcoal_ParamTrait::validateSandbox( 1, $sandbox );

        $this->sandbox = $sandbox;

        parent::__construct();
    }

    /**
     * get configuration data by key
     *
     * @param string[] $keys           key list
     * @param Charcoal_ObjectPath $obj_path         object path
     * @param string $type_name        type name of the object
     *
     * @return mixed              configuration data
     */
    public function get( array $keys, $obj_path, $type_name )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );

        // get config povier
        $provider = $this->sandbox->getConfigProvider();

        // make cache file path
        $base_dir = 'config/' . CHARCOAL_PROJECT . '/' . CHARCOAL_APPLICATION . '/' . $type_name;
        $real_path = $obj_path->getRealPath();
        $base_dir = empty($real_path) ? $base_dir : $base_dir . '/' . $obj_path->getRealPath();
        $cache_file = $obj_path->getObjectName() . '.config.php';
        $cache_file_path = CHARCOAL_CACHE_DIR . '/' . $base_dir . '/' . $cache_file;

        // if cache file is not found, read config file.
        if ( !is_readable($cache_file_path) ){
            goto LOAD_CONFIG_FROM_FILE;
        }

        // read cache file
        $fp = fopen($cache_file_path, 'r');
        if ( !$fp ){
            goto LOAD_CONFIG_FROM_FILE;
        }
        flock($fp, LOCK_EX);
        $contents = '';
        while (!feof($fp)) {
            $contents .= fread($fp, 1024);
        }
        flock($fp, LOCK_UN);
        fclose($fp);

        // eval contents
        $cached_config = null;
        $retval = null;
        try{
            $retval = @eval('$cached_config='.$contents.'; return true;');
        }
        catch( ParseError $e ){
            goto LOAD_CONFIG_FROM_FILE;
        }

        if ( $retval && is_array($cached_config) )
        {
            // read cache created date
            $cache_date = isset($cached_config['cache_date']) ? $cached_config['cache_date'] : false;

            // get each config
            $config_all = array();
            foreach( $keys as $key )
            {
                // cache entry
                $cache_exists = isset($cached_config['config'][$key]);

                // if cache data does not exists, then load from file
                if ( !$cache_exists ){
                    goto LOAD_CONFIG_FROM_FILE;
                }

                // read config file date
                $config_date = $provider->getConfigDate( $key );

                // if config file does not exist, then load from file
                if ( !$config_date ){
                    goto LOAD_CONFIG_FROM_FILE;
                }

                // check config file's date
                if ( $config_date > $cache_date ){
                    goto LOAD_CONFIG_FROM_FILE;
                }

                // correct cached data
                $cache_data = $cached_config['config'][$key];
                $config_all = is_array($cache_data) ? array_merge( $config_all, $cache_data ) : $config_all;
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

        // create cache root directory if not exists
        if ( !file_exists(CHARCOAL_CACHE_DIR) ){
            $res = @mkdir(CHARCOAL_CACHE_DIR);
            if ( !$res ) {
                _throw( new Charcoal_RegistryException('file_system', 'mkdir failed:'.CHARCOAL_CACHE_DIR) );
            }
        }

        // create cache directory recursively
        $dirs = explode('/',$base_dir);
        $dir_walk = '';
        foreach( $dirs as $d ){
            if ( empty($d) )    continue;
            $dir_walk .= '/' . $d;
            $checkdir = CHARCOAL_CACHE_DIR . $dir_walk;
            if ( !file_exists($checkdir) ){
                $res = @mkdir($checkdir);
                if ( !$res ) {
                    _throw( new Charcoal_RegistryException('file_system', 'mkdir failed:'.$checkdir) );
                }
            }
        }

        // make new cache data
        $new_cache = array(
                'cache_date' => time(),
                'config' => $config_by_key,
            );
        $lines = var_export($new_cache, true);

        // create cache file
        $fp = @fopen($cache_file_path, 'c');
        if ( !$fp ) {
            _throw( new Charcoal_RegistryException('file_system', 'failed to open cache file:'.$cache_file_path) );
        }

        flock($fp, LOCK_EX);
        ftruncate($fp, 0);
        fputs($fp, $lines);
        flock($fp, LOCK_UN);
        fclose($fp);

        return $config_all;
    }

    /**
     * list objects in target directory
     *
     * @param string $path             path
     * @param string $type_name        type name of the object
     *
     * @return string[]            virtual paths of found objects
     */
    public function listObjects( $path, $type_name )
    {
        // get config povier
        $provider = $this->sandbox->getConfigProvider();

        return $provider->listObjects( $path, $type_name );
    }
}

