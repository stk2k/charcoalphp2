<?php
/**
* interface of container
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_IContainer
{
	/**
	 * get component
	 * 
	 * @param Charcoal_String $key         key of the class
	 */
	public function getComponent( $key );

	/**
	 * destruct instance
	 */
	public function terminate();
}

