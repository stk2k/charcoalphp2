<?php
/**
* Module interface
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

interface Charcoal_IModule extends Charcoal_ICharcoalObject
{
	/*
	 * get required module names
	 */
	public function getRequiredModules();

	/*
	 * load tasks in module directory
	 */
	public function loadTasks( Charcoal_ITaskManager $task_manager );

	/*
	 * load events in module directory
	 */
	public function loadEvents( Charcoal_ITaskManager $task_manager );
}

