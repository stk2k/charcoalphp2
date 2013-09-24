<?php
/**
*
* config provider implementation of .ini file(parse_ini_file)
*
* PHP version 5
*
* @package    config_providers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
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
//		Charcoal_ParamTrait::checkProperties( 1, $options );
		if ( is_array( $options ) ){
			$options = new Charcoal_Config( $options );
		}

		$this->debug = $options->getBoolean( 'debug', FALSE );
	}

	/**
	 *  load config
	 *
	 * @param  string $key          config key
	 *
	 * @return mixed   configure data
	 */
	public function loadConfig( $key )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );

		$source = $key . '.ini';

		// check if ini file exists
		if ( !is_file($source) ){
			if ( $this->debug === TRUE )	print "[$source]is not exists!" . eol();	
//			log_info( "system, debug, config", "config", "ini file[$source] does not exist." );
			return NULL;
		}

		// read ini file
	    $ini_config = parse_ini_file( $source, TRUE );
		if ( $this->debug === TRUE ){
			print "[$source] parse_ini_file($source)=" . eol();
			ad( $ini_config );
		}
		if ( $ini_config === FALSE ){	
//			log_warning( "system, debug, config", "config", "failed to read ini file[$source]" );
			return NULL;
		}
//		log_info( "system, debug, config", "config", "read ini file[$source]:" . print_r($ini_config,true) );

		return $ini_config;
	}

}

