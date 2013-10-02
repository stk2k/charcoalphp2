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
	public function setOptions( $options );

	/**
	 *  load config
	 *
	 * @param  string $key       config key
	 *
	 * @return array   configure data
	 */
	public function loadConfig( $key );

}

