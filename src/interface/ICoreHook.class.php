<?php
/**
* Core hook object interface
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_ICoreHook extends Charcoal_ICharcoalObject
{
	/**
	 * process core hook message
	 * 
	 * @param int $hook_stage      hook stage
	 * @param mixed $data          additional data
	 */
	public function processMessage( $hook_stage, $data );
}

