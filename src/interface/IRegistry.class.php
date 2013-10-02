<?php
/**
* interface of registry
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_IRegistry
{
	/**
	 * get configuration data by key
	 * 
	 * @param string $key      registry data id
	 *
	 * @return array           configuration data
	 */
	public function get( $key );

}

