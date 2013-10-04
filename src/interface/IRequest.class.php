<?php
/**
* interface of request object
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IRequest extends Charcoal_ICharcoalObject, Iterator, ArrayAccess
{
	/**
	 *  Retrieve current procedure path
	 */
	public function getProcedurePath();

}

