<?php
/**
* Router interface
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_IRouter extends Charcoal_ICharcoalObject
{

	/**
	 * Lookup routing rules
	 *
	 * @return Charcoal_Boolean TRUE if any rule is matched, otherwise FALSE
	 */
	public function route( Charcoal_IRequest $request, Charcoal_IRoutingRule $rule );
}

