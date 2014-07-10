<?php
/**
* interface of config provider
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

interface Charcoal_IConfigProvider
{
	/**
	 * set options
	 *
	 * @param Charcoal_Properties $options   option set to apply
	 */
	public function setOptions( $options );

	/**
	 *  load config
	 *
	 * @param  string|Charcoal_String $key                  config key
	 * @param  Charcoal_RegistryAccessLog $access_log       registry access log
	 *
	 * @return array   configure data
	 */
	public function loadConfig( $key, $access_log = NULL );

}

