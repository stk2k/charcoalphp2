<?php
/**
* interface of request object
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_IRequest extends Charcoal_ICharcoalObject, Iterator, ArrayAccess
{
	/**
	 *  Retrieve current procedure path
	 */
	public function getProcedurePath();

}

