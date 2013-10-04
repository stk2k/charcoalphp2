<?php
/**
* interface of environment
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IEnvironment
{
	/**
	 * get environment data by key
	 * 
	 * @param string $key      environment data id
	 *
	 * @return string           configuration data
	 */
	public function get( $key );

	/**
	 * update environment data by key
	 * 
	 * @param string $key      environment data id
	 * @param string $value    value to update
	 */
	public function set( $key, $value );

	/**
	 * replace string by environment values
	 * 
	 * @param string $str       target string for replacing
	 *
	 * @return string           replaced string
	 */
	public function replace( $str );

}

