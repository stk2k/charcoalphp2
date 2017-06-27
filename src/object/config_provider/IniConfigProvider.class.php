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
class Charcoal_IniConfigProvider extends Charcoal_AbstractConfigProvider
{
    const TAG = 'ini_config_provider';

    private $debug;

    /**
     * set options
     *
     * @param Charcoal_HashMap $options   option set to apply
     */
    public function setOptions( $options )
    {
        $this->debug = $options->getBoolean( 'debug', FALSE );
    }

    /**
     *  get config last updated date
     *
     * @param  string|Charcoal_String $key                  config key
     *
     * @return int|NULL     last updated date(UNIX timestamp), or FALSE if file does not exist
     */
    public function getConfigDate( $key )
    {
        $source = $key . '.ini';

        return is_file($source) ? filemtime($source) : false;
    }

    /**
     *  load config
     *
     * @param  string|Charcoal_String $key                  config key
     *
     * @return mixed   configure data
     */
    public function loadConfig( $key )
    {
//        Charcoal_ParamTrait::validateString( 1, $key );

        $source = $key . '.ini';

        $is_debug = b($this->debug)->isTrue();

        $result = NULL;
        if ( !is_file($source) ){
            if ( $is_debug ){
                print "ini file[$source] does not exist." . eol();
                log_warning( "system, debug, config", "config", "ini file[$source] does not exist." );
            }
        }
        else{
            // read ini file
            $result = @parse_ini_file( $source, TRUE );
            if ( $is_debug ){
                print "[$source] parse_ini_file($source)=" . eol();
                ad( $result );

                if ( $result === FALSE ){
                    print "parse_ini_file failed: [$source]" . eol();
                    log_warning( "system, debug, config", "config", "parse_ini_file failed: [$source]" );
                }
                else{
                    log_debug( "system, debug, config", "config", "read ini file[$source]:" . print_r($result,true) );
                }
            }
        }

        return $result;
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
        $config_file_tail = '.' . $type_name . '.ini';
        $tail_length = strlen($config_file_tail);

        $object_list = array();
        if ( is_dir($path) && $dh = opendir($path) )
        {
            log_debug( "system, debug, config", "open directory: $path", self::TAG );
            while( ($file = readdir($dh)) !== FALSE )
            {
                if ( $file === '.' || $file === '..' )    continue;

                if ( strrpos($file,$config_file_tail) === strlen($file) - $tail_length ){
                    $object_name = substr( $file, 0, strlen($file) - $tail_length );
                    $object_list[] = $object_name;
                }
            }
            closedir($dh);
        }

        return $object_list;
    }
}

