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

	/*
	 *	set object options
	 */
	public function setOptions( Charcoal_Properties $options )
	{
		$this->_debug = $options->getBoolean( s('debug'), b(FALSE) );
	}

	/**
	 *  load config by name
	 *
	 * @access    public
	 * @param     config_root   root name
	 * @param     config_name   config name
	 */
	public function loadConfigByName( 
							Charcoal_String $config_root, 
							Charcoal_String $config_name, 
							Charcoal_Config& $config
							)
	{
		$config_root = us($config_root);
		$config_name = us($config_name);

		$source = $config_root . $config_name . '.ini';

		// check if ini file exists
		if ( !is_file($source) ){	
			if ( $this->_debug->isTrue() )	print "[$source]is not exists!" . eol();	
//			log_info( "system, debug, config", "config", "ini file[$source] does not exist." );
			return FALSE;
		}

		// read ini file
	    $ini_config = parse_ini_file( $source, TRUE );
		if ( $this->_debug->isTrue() ){
			print "[$source] parse_ini_file($source)=" . eol();
			ad( $ini_config );
		}
		if ( $ini_config === FALSE ){	
//			log_warning( "system, debug, config", "config", "failed to read ini file[$source]" );
			return FALSE;
		}
//		log_info( "system, debug, config", "config", "read ini file[$source]:" . print_r($ini_config,true) );

		// load ini file
		foreach( $ini_config as $key => $value ){
			$config->set( s($key), $value );
		}

		return TRUE;
	}

}

