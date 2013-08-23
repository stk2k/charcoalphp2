<?php
/**
* Core hook object interface
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_ICoreHook extends Charcoal_ICharcoalObject
{
	/**
	 * Process core hook message
	 */
	public function process( Charcoal_CoreHookMessage $msg );
}

return __FILE__;