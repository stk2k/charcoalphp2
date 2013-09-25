<?php
/**
* Interface of collection classes
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_ICollection
{
	/**
	 *	Get all values with keys
	 *
	 * @return array
	 */
	public function getAll();

	/**
	 *	Applies a callback to all elements
	 *
	 * @return array
	 */
	public function map( $callable );

}

