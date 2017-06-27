<?php
/**
* 基本的なモジュール実装
*
* PHP version 5
*
* @package    class.core
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_SimpleModule extends Charcoal_CharcoalComponent implements Charcoal_IModule
{
    /** @var Charcoal_Vector */
    private $tasks;
    
    /** @var Charcoal_Vector */
    private $events;
    
    /** @var Charcoal_Vector */
    private $required_modules;

    /*
     *    コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    /*
     * Initialize instance
     *
     * @param array $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );
        
        $config = new Charcoal_HashMap($config);

        $this->tasks              = $config->getArray( 'tasks', array() );
        $this->events             = $config->getArray( 'events', array() );
        $this->required_modules   = $config->getArray( 'required_modules', array() );

//        log_info( "system, debug", "module", "tasks:" . print_r($this->tasks,true) );
//        log_info( "system, debug", "module", "events:" . print_r($this->events,true) );
//        log_info( "system, debug", "module", "required_modules:" . print_r($this->extends,true) );
    }

    /*
     * get required module names
     */
    public function getRequiredModules()
    {
        return $this->required_modules;
    }

    /**
     * load a rask
     *
     * @param Charcoal_ObjectPath $obj_path
     * @param Charcoal_String|string $path
     * @param Charcoal_ITaskManager $task_manager
     *
     * @return Charcoal_ITask|NULL
     */
    private function loadTask( $obj_path, $path, $task_manager )
    {
//        Charcoal_ParamTrait::validateObjectPath( 1, $obj_path );
//        Charcoal_ParamTrait::validateString( 2, $path );
//        Charcoal_ParamTrait::validateImplements( 3, 'Charcoal_ITaskManager', $task_manager );

        // file base name
        $base_name = basename( $path );

        // retrieve class name from file name
        $pos = strpos( $base_name, '.class.php' );

        $class_name = substr( $base_name, 0, $pos );

        // include source file
        Charcoal_Framework::loadSourceFile( $path );

        // create new instance
        $klass = new Charcoal_Class( $class_name );
        $task = $klass->newInstance();

        // check if th object implements Charcoal_Task interface
        if ( !($task instanceof Charcoal_Task) ){
            // Invoke Exception
            _throw( new Charcoal_InterfaceImplementException( $task, 'Charcoal_Task' ) );
        }

        log_info( 'system, event, debug', "created task[$task] in module[$obj_path]");

        // build object path for the task
        $obj_name = $task->getObjectName();
        $task_path = s($obj_name . '@' . $obj_path->getVirtualPath());

        // set task property
        $task->setObjectPath( $task_path );
        $task->setTypeName( 'task' );
        $task->setSandbox( $this->getSandbox() );

        // load object config
        $config = Charcoal_ConfigLoader::loadConfig( $this->getSandbox()->getRegistry(), $task_path, 'task' );

        // configure task
        $task->configure( $config );
//        log_info( 'system, event, debug', "task[$task] configured.");

        // regiser task
        $task_manager->registerTask( $task_path, $task );
        log_info( 'system, event, debug', "task[$class_name] registered as: [$task_path]");

        return $task;
    }

    /*
     * load a rask
     */
    private function loadEvent( $obj_path, $path, $task_manager )
    {
        // file base name
        //$base_name = basename( $path );

        // retrieve class name from file name
        //$pos = strpos( $base_name, '.class.php' );

        //$class_name = substr( $base_name, 0, $pos );

        // include source file
        Charcoal_Framework::loadSourceFile( $path );

/*

        // create new instance
        $klass = new Charcoal_Class( $class_name );
        $event = $klass->newInstance();

        log_info( 'system, event, debug', "module", "created event[$event] in module[$obj_path]");

        // build object path for the event
        $obj_name = $event->getObjectName();
        $event_path = $obj_name . '@' . $obj_path->getVirtualPath();
//        $event_path = new Charcoal_ObjectPath( $event_path );
//        log_info( 'system, event, debug', "module", "event[$event] path: [$event_path]");

        // set task property
        $event->setObjectPath( $event_path );
        $event->setTypeName( 'event' );

        // load object config
        $config = Charcoal_ConfigLoader::loadConfig( $this->getSandbox(), $event_path, 'event' );
        $config = new Charcoal_Config( $this->getSandbox()->getEnvironment(), $config );

        // configure event
        $event->configure( $config );
//        log_info( 'system, event, debug', "module", "event[$event] configured.");

*/

        return $obj_path;
    }

    /**
     * load tasks in module directory
     *
     * @param Charcoal_ITaskManager $task_manager
     *
     * @return int           count of loaded tasks
     */
    public function loadTasks( $task_manager )
    {
        $loadedtasks = NULL;

        $root_path = $this->getObjectPath();

        //==================================
        // load task source code
        //==================================

        $real_path = $root_path->getRealPath();

        $webapp_path    = Charcoal_ResourceLocator::getApplicationPath( 'module' . $real_path );
        $project_path   = Charcoal_ResourceLocator::getProjectPath( 'module' . $real_path );
        $framework_path = Charcoal_ResourceLocator::getFrameworkPath( 'module' . $real_path );

        //log_info( 'system, event, debug', "module", "webapp_path: $webapp_path");
        //log_info( 'system, event, debug', "module", "project_path: $project_path");
        //log_info( 'system, event, debug', "module", "framework_path: $framework_path");

        $task_class_suffix = 'Task.class.php';

        $task = NULL;

        if ( is_dir($webapp_path) && $dh = opendir($webapp_path) )
        {
            log_info( 'system, event, debug', "module", "webapp_path is existing.");
            while( ($file = readdir($dh)) !== FALSE )
            {
                if ( $file === '.' || $file === '..' )    continue;
                if ( strpos($file,$task_class_suffix) !== FALSE ){
                    $loadedtasks[] = $this->loadTask( $root_path, "$webapp_path/$file", $task_manager );
                }
            }
            closedir($dh);
        }

        if ( is_dir($project_path) && $dh = opendir($project_path) )
        {
            log_info( 'system, event, debug', "module", "project_path is existing.");
            while( ($file = readdir($dh)) !== FALSE )
            {
                if ( $file === '.' || $file === '..' )    continue;
                if ( strpos($file,$task_class_suffix) !== FALSE ){
                    $loadedtasks[] = $this->loadTask( $root_path, "$project_path/$file", $task_manager );
                }
            }
            closedir($dh);
        }

        if ( is_dir($framework_path) && $dh = opendir($framework_path) )
        {
            log_info( 'system, event, debug', "module", "framework_path is existing.");
            while( ($file = readdir($dh)) !== FALSE )
            {
                if ( $file === '.' || $file === '..' )    continue;
                if ( strpos($file,$task_class_suffix) !== FALSE ){
                    $loadedtasks[] = $this->loadTask( $root_path, "$framework_path/$file", $task_manager );
                }
            }
            closedir($dh);
        }

        //==================================
        // create and register tasks
        //==================================

        // load tasks from config file
        if ( $this->tasks ){
            foreach( $this->tasks as $task ){
                
                if ( empty($task) ){
                    continue;
                }
                
                $task_path = $task . '@' . $root_path->getVirtualPath();
                
                /** @var Charcoal_Itask $task */
                $task = $this->getSandbox()->createObject( $task_path, 'task' );

                $task_manager->registerTask( $task_path, $task );

                $loadedtasks[] = $task;
            }
        }

        return count($loadedtasks);
    }

    /**
     * load events in module directory
     *
     * @param Charcoal_ITaskManager $task_manager
     *
     * @return int           count of loaded events
     */
    public function loadEvents( $task_manager )
    {
        $loadedevents = NULL;

        $root_path = $this->getObjectPath();

        //==================================
        // load event source code
        //==================================

        $real_path = $root_path->getRealPath();

        $webapp_path    = Charcoal_ResourceLocator::getApplicationPath( 'module' . $real_path );
        $project_path   = Charcoal_ResourceLocator::getProjectPath( 'module' . $real_path );
        $framework_path = Charcoal_ResourceLocator::getFrameworkPath( 'module' . $real_path );

        $event_class_suffix = 'Event.class.php';

        if ( is_dir($webapp_path) && $dh = opendir($webapp_path) )
        {
            while( ($file = readdir($dh)) !== FALSE )
            {
                if ( $file === '.' || $file === '..' )    continue;
                if ( strpos($file,$event_class_suffix) !== FALSE ){
                    $loadedevents[] = $this->loadEvent( $root_path, "$webapp_path/$file", $task_manager );
                }
            }
            closedir($dh);
        }

        if ( is_dir($project_path) && $dh = opendir($project_path) )
        {
            while( ($file = readdir($dh)) !== FALSE )
            {
                if ( $file === '.' || $file === '..' )    continue;
                if ( strpos($file,$event_class_suffix) !== FALSE ){
                    $loadedevents[] = $this->loadEvent( $root_path, "$project_path/$file", $task_manager );
                }
            }
            closedir($dh);
        }

        if ( is_dir($framework_path) && $dh = opendir($framework_path) )
        {
            while( ($file = readdir($dh)) !== FALSE )
            {
                if ( $file === '.' || $file === '..' )    continue;
                if ( strpos($file,$event_class_suffix) !== FALSE ){
                    $loadedevents[] = $this->loadEvent( $root_path, "$framework_path/$file", $task_manager );
                }
            }
            closedir($dh);
        }

        //==================================
        // create and register events
        //==================================

        // load events from config file
//        log_info( "system,debug", "module", "loading events in module file:" . $this->events );

        if ( $this->events ){
            foreach( $this->events as $event ){
                if ( empty($event) ){
                    continue;
                }
    
                $event_path = $event . '@' . $root_path->getVirtualPath();

                $event = $this->getSandbox()->createEvent( $event_path );

//                log_info( "system,debug", "module", "created event[" . get_class($event) . "/$event_path] in module[$root_path]");

                $loadedevents[] = $event;
            }
        }

//        log_info( "system,debug", "module", "loaded events: " . print_r($loadedevents,true) );

        return count($loadedevents);
    }

}

