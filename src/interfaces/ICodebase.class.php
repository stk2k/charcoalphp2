<?php
/**
* interface of codebase
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_ICodebase
{
	/**
	 * get class source
	 * 
	 * @param Charcoal_String $path         virtual path of the class
	 */
	public function getClassSource( $path );
}

