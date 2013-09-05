<?php
/**
* interface of config provider
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

interface Charcoal_IConfigProvider
{
	/**
	 * set options
	 *
	 * @param Charcoal_Properties $options   option set to apply
	 */
	public function setOptions( Charcoal_Properties $options );

	/**
	 *  load config
	 *
	 * @param  Charcoal_String $config_root   root name
	 * @param  Charcoal_String $config_name   config name
	 *
	 * @return mixed   configure data
	 */
	public function loadConfig( Charcoal_String $config_root, Charcoal_String $config_name );

}

