<?php
/**
* Interface of file filteer
* 
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_IFileFilter
{
	/**
	 * Check if the filter select the specified file.
	 *
	 * @param Charcoal_File $file         Target fileto be tested.
	 */
	public function accept( Charcoal_File $file );

}

return __FILE__;