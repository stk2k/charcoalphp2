<?php
/**
* タスク
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class NewTaskEvent extends Charcoal_Event
{
    private $task_name;
    private $module_path;
    private $app_name;
    private $project_name;
    private $target_dir;

    /**
     *    constructor
     *
     *    @param string|Charcoal_String $task_name      task name
     *    @param string|Charcoal_String $module_name    module path
     *    @param string|Charcoal_String $app_name       application name
     *    @param string|Charcoal_String $project_name   project name
     *    @param string|Charcoal_String $target_dir     target directory
     */
    public function __construct( $task_name, $module_path, $app_name, $project_name, $target_dir )
    {
        parent::__construct();

        $this->task_name    = us($task_name);
        $this->module_path  = us($module_path);
        $this->app_name     = us($app_name);
        $this->project_name = us($project_name);
        $this->target_dir   = us($target_dir);
    }

    /**
     * get task name
     *
     * @return string    task name
     */
    public function getTaskName()
    {
        return $this->task_name;
    }

    /**
     * get module path
     *
     * @return string    module path
     */
    public function getModulePath()
    {
        return $this->module_path;
    }

    /**
     * get application name
     *
     * @return string    application name
     */
    public function getAppName()
    {
        return $this->app_name;
    }

    /**
     * get project name
     *
     * @return string    project name
     */
    public function getProjectName()
    {
        return $this->project_name;
    }

    /**
     * get target directory
     *
     * @return string    target directory
     */
    public function getTargetDir()
    {
        return $this->target_dir;
    }

}

return __FILE__;