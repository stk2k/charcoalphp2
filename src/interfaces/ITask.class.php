<?php
/**
* Task Interface
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_ITask extends Charcoal_ICharcoalObject
{

	/**
	 * Name space for sequence
	 *
	 * @return string 
	 */
	public function getNameSpace();

	/**
	 * Guard conditions in task scheduling
	 */
	public function getGuardConditions();

	/**
	 * Get event filter list
	 */
	public function getEventFilters();

	/**
	 * Get post action of task execution
	 */
	public function getPostActions();

	/**
	 * Get prioriy of task scheduling
	 */
	public function getPriority();

	/**
	 * Process events
	 *
	 * @param Charcoal_IEventContext $context   event context
	 */
	public function processEvent( Charcoal_IEventContext $context );

}
