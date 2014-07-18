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
//		Charcoal_ParamTrait::checkProperties( 1, $options, TRUE );

		if ( is_array( $options ) || $options === NULL ){
			$options = new Charcoal_Config( $this->getSandbox()->getEnvironment(), $options );
		}

		$this->debug = $options->getBoolean( 'debug', FALSE );
	}

	/**
	 *  load config
	 *
	 * @param  string|Charcoal_String $key                  config key
	 * @param  Charcoal_RegistryAccessLog $access_log       registry access log
	 *
	 * @return mixed   configure data
	 */
	public function loadConfig( $key, $access_log = NULL )
	{
		Charcoal_ParamTrait::checkString( 1, $key );
		Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_RegistryAccessLog', $access_log, TRUE );

		$source = $key . '.ini';

		$is_debug = b($this->debug)->isTrue();

	    $result = NULL;
		if ( !is_file($source) ){
			if ( $is_debug ){
				print "ini file[$source] does not exist." . eol();
				log_warning( "system, debug, config", "config", "ini file[$source] does not exist." );
			}
			if ( $access_log ){
				$access_log->addLog($source . '[**NOT FOUND**]', Charcoal_EnumRegistryAccessLogResult::E_NOT_FOUND );
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
			if ( $access_log ){
				if ( $result === FALSE ){
					$access_log->addLog($source . '[**FAILED**]', Charcoal_EnumRegistryAccessLogResult::E_FAILED );
				}
				else{
					$access_log->addLog($source . '[SUCCESS]', Charcoal_EnumRegistryAccessLogResult::SUCCESS );
				}
			}
		}

		return $result;
	}

}

