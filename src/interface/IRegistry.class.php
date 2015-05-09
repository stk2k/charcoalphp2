<?php
/**
* interface of registry
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IRegistry
{
	/**
	 * get configuration data by key
	 * 
	 * @param string[] $keys           key list
	 * @param string $obj_path         object path
	 * @param string $type_name        type name of the object
	 *
	 * @return mixed              configuration data
	 */
	public function get( array $keys, $obj_path, $type_name );

}

