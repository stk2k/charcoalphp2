<?php
/**
* Module interface
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

interface Charcoal_IModule extends Charcoal_ICharcoalObject
{
    /**
     * get required module names
     *
     * @return string[]
     */
    public function getRequiredModules();

    /**
     * load tasks in module directory
     *
     * @param Charcoal_ITaskManager $task_manager
     *
     * @return int           count of loaded tasks
     */
    public function loadTasks( $task_manager );

    /**
     * load events in module directory
     *
     * @param Charcoal_ITaskManager $task_manager
     *
     * @return int           count of loaded events
     */
    public function loadEvents( $task_manager );
}

