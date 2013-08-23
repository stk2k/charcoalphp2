<?php
/**
* token generator interface
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_ITokenGenerator extends Charcoal_ICharcoalObject
{

	/**
	 * generate a token
	 */
	public function generateToken( Charcoal_HashMap $options = NULL );
}

return __FILE__;