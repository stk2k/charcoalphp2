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
class Charcoal_IniConfigProvider extends Charcoal_CharcoalObject implements Charcoal_IConfigProvider
{
	private $_debug;

	/**
	 * set options
	 *
	 * @param Charcoal_Properties $options   option set to apply
	 */
	public function setOptions( Charcoal_Properties $options )
	{
		$this->_debug = $options->getBoolean( s('debug'), b(FALSE) );
	}

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
		$config_root = us($config_root);
		$config_name = us($config_name);

		$source = $config_root . $config_name . '.ini';

		// check if ini file exists
		if ( !is_file($source) ){	
			if ( $this->_debug->isTrue() )	print "[$source]is not exists!" . eol();	
//			log_info( "system, debug, config", "config", "ini file[$source] does not exist." );
			return NULL;
		}

		// read ini file
	    $ini_config = parse_ini_file( $source, TRUE );
		if ( $this->_debug->isTrue() ){
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

