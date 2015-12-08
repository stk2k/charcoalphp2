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
    private $debug;

    /**
     * set options
     *
     * @param Charcoal_Properties $options   option set to apply
     */
    public function setOptions( $options )
    {
//        Charcoal_ParamTrait::validateProperties( 1, $options, TRUE );

        if ( is_array( $options ) || $options === NULL ){
            $options = new Charcoal_Config( $this->getSandbox()->getEnvironment(), $options );
        }

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

}

