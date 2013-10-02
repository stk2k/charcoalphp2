<?php
/**
* 基本的なモジュール実装
*
* PHP version 5
*
* @package    class.core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_SimpleModule extends Charcoal_CharcoalObject implements Charcoal_IModule
{
	private $_tasks;
	private $_extends;
	private $_required_modules;

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/*
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$this->_tasks              = $config->getArray( 'tasks', array() );
		$this->_events             = $config->getArray( 'events', array() );
		$this->_required_modules   = $config->getArray( 'required_modules', array() );

//		log_info( "system, debug", "module", "tasks:" . print_r($this->_tasks,true) );
//		log_info( "system, debug", "module", "events:" . print_r($this->_events,true) );
//		log_info( "system, debug", "module", "required_modules:" . print_r($this->_extends,true) );
	}

	/*
	 * get required module names
	 */
	public function getRequiredModules()
	{
		return $this->_required_modules;
	}

	/*
	 * load a rask
	 */
	private function loadTask( $obj_path, $path, $task_manager )
	{
//		Charcoal_ParamTrait::checkObjectPath( 1, $obj_path );
//		Charcoal_ParamTrait::checkString( 2, $path );
//		Charcoal_ParamTrait::checkImplements( 3, 'Charcoal_ITaskManager', $task_manager );

		// file base name
		$base_name = basename($path);

		// retrieve class name from file name
		$pos = strpos($base_name,CHARCOAL_CLASS_FILE_SUFFIX);

		$class_name = substr($base_name,0,$pos);

		// include source file
		Charcoal_Framework::loadSourceFile( $path );

		// create new instance
		$klass = new Charcoal_Class( $class_name );
		$task = $klass->newInstance();

//		log_info( "system,debug,task", "created task[$task] in module[$obj_path]");

		// build object path for the task
		$obj_name = $task->getObjectName();
		$task_path = s($obj_name . '@' . $obj_path->getVirtualPath());

		// set task property
		$task->setObjectPath( $task_path );
		$task->setTypeName( 'task' );
		$task->setSandbox( $this->getSandbox() );

		// load object config
		$config = Charcoal_ConfigLoader::loadConfig( $this->getSandbox(), $task_path, 'task' );
		$config = new Charcoal_Config( $config );

		// configure task
		$task->configure( $config );
//		log_info( "system,debug,task", "task[$task] configured.");

		// regiser task
		$task_manager->registerTask( $task_path, $task );
		log_info( "system,debug,task", "task[$class_name] registered as: [$task_path]");

		return $task;
	}

	/*
	 * load a rask
	 */
	private function loadEvent( $obj_path, $path, $task_manager )
	{
//		Charcoal_ParamTrait::checkObjectPath( 1, $obj_path );
//		Charcoal_ParamTrait::checkString( 2, $path );
//		Charcoal_ParamTrait::checkImplements( 3, 'Charcoal_ITaskManager', $task_manager );

		// file base name
		$base_name = basename($path);

		// retrieve class name from file name
		$pos = strpos($base_name,CHARCOAL_CLASS_FILE_SUFFIX);

		$class_name = substr($base_name,0,$pos);

		// include source file
		Charcoal_Framework::loadSourceFile( $path );

		// create new instance
		$klass = new Charcoal_Class( $class_name );
		$event = $klass->newInstance();

//		log_info( "system,debug,event", "module", "created event[$event] in module[$obj_path]");

		// build object path for the event
		$obj_name = $event->getObjectName();
		$event_path = $obj_name . '@' . $obj_path->getVirtualPath();
//		$event_path = new Charcoal_ObjectPath( $event_path );
//		log_info( "system,debug,event", "module", "event[$event] path: [$event_path]");

		// set task property
		$event->setObjectPath( $event_path );
		$event->setTypeName( 'event' );

		// load object config
		$config = Charcoal_ConfigLoader::loadConfig( $this->getSandbox(), $event_path, 'event' );
		$config = new Charcoal_Config( $config );

		// configure event
		$event->configure( $config );
//		log_info( "system,debug,event", "module", "event[$event] configured.");

		// add event 
		$task_manager->pushEvent( $event );
		log_info( "system,debug,event", "module", "event[$event] added to task manager.");

		return $event;
	}

	/*
	 * load tasks in module directory
	 */
	public function loadTasks( Charcoal_ITaskManager $task_manager )
	{
		$loaded_tasks = NULL;

		$root_path = $this->getObjectPath();

		//==================================
		// load task source code
		//==================================

		$real_path = $root_path->getRealPath();

		$webapp_path    = Charcoal_ResourceLocator::getApplicationPath( 'module' . $real_path );
		$project_path   = Charcoal_ResourceLocator::getProjectPath( 'module' . $real_path );
		$framework_path = Charcoal_ResourceLocator::getFrameworkPath( 'module' . $real_path );

		$task_class_suffix = 'Task' . CHARCOAL_CLASS_FILE_SUFFIX;

		$task = NULL;

		if ( is_dir($webapp_path) && $dh = opendir($webapp_path) )
		{
			while( ($file = readdir($dh)) !== FALSE )
			{
				if ( $file === '.' || $file === '..' )	continue;
				if ( strpos($file,$task_class_suffix) !== FALSE ){
					$loaded_tasks[] = $this->loadTask( $root_path, "$webapp_path/$file", $task_manager );
				}
			}
			closedir($dh);
		}

		if ( is_dir($project_path) && $dh = opendir($project_path) )
		{
			while( ($file = readdir($dh)) !== FALSE )
			{
				if ( $file === '.' || $file === '..' )	continue;
				if ( strpos($file,$task_class_suffix) !== FALSE ){
					$loaded_tasks[] = $this->loadTask( $root_path, "$project_path/$file", $task_manager );
				}
			}
			closedir($dh);
		}

		if ( is_dir($framework_path) && $dh = opendir($framework_path) )
		{
			while( ($file = readdir($dh)) !== FALSE )
			{
				if ( $file === '.' || $file === '..' )	continue;
				if ( strpos($file,$task_class_suffix) !== FALSE ){
					$loaded_tasks[] = $this->loadTask( $root_path, "$framework_path/$file", $task_manager );
				}
			}
			closedir($dh);
		}

		//==================================
		// create and register tasks
		//==================================

		// load tasks from config file
//		log_info( "system,debug", "module", "loading tasks in module:" . $root_path );

		if ( $this->_tasks ){
			foreach( $this->_tasks as $task ){
				$task_path = $task . '@' . $root_path->getVirtualPath();
//				log_info( "system,debug", "module", "creating task[$task_path] in module[$root_path]");

				$task = $this->getSandbox()->createObject( $task_path, 'task' );

//				log_info( "system,debug", "module", "created task[" . get_class($task) . "/$task_path] in module[$root_path]");

				$task_manager->registerTask( s($task_path), $task );

				$loaded_tasks[] = $task;
			}
		}

//		log_info( "system,debug", "module", "loaded tasks: " . print_r($loaded_tasks,true) );

		return $loaded_tasks;
	}

	/*
	 * load events in module directory
	 */
	public function loadEvents( Charcoal_ITaskManager $task_manager )
	{
		$loaded_events = NULL;

		$root_path = $this->getObjectPath();

		//==================================
		// load event source code
		//==================================

		$real_path = $root_path->getRealPath();

		$webapp_path    = Charcoal_ResourceLocator::getApplicationPath( 'modules' . $real_path );
		$project_path   = Charcoal_ResourceLocator::getProjectPath( 'modules' . $real_path );
		$framework_path = Charcoal_ResourceLocator::getFrameworkPath( 'modules' . $real_path );

		$event_class_suffix = 'Event' . CHARCOAL_CLASS_FILE_SUFFIX;

		if ( is_dir($webapp_path) && $dh = opendir($webapp_path) )
		{
			while( ($file = readdir($dh)) !== FALSE )
			{
				if ( $file === '.' || $file === '..' )	continue;
				if ( strpos($file,$event_class_suffix) !== FALSE ){
					$loaded_events[] = $this->loadEvent( $root_path, "$webapp_path/$file", $task_manager );
				}
			}
			closedir($dh);
		}

		if ( is_dir($project_path) && $dh = opendir($project_path) )
		{
			while( ($file = readdir($dh)) !== FALSE )
			{
				if ( $file === '.' || $file === '..' )	continue;
				if ( strpos($file,$event_class_suffix) !== FALSE ){
					$loaded_events[] = $this->loadEvent( $root_path, "$project_path/$file", $task_manager );
				}
			}
			closedir($dh);
		}

		if ( is_dir($framework_path) && $dh = opendir($framework_path) )
		{
			while( ($file = readdir($dh)) !== FALSE )
			{
				if ( $file === '.' || $file === '..' )	continue;
				if ( strpos($file,$event_class_suffix) !== FALSE ){
					$loaded_events[] = $this->loadEvent( $root_path, "$framework_path/$file", $task_manager );
				}
			}
			closedir($dh);
		}

		//==================================
		// create and register events
		//==================================

		// load events from config file
//		log_info( "system,debug", "module", "loading events in module file:" . $this->_events );

		if ( $this->_events ){
			foreach( $this->_events as $event ){
				$event_path = $event . '@' . $root_path->getVirtualPath();

				$event = $this->getSandbox()->createEvent( $event_path );

//				log_info( "system,debug", "module", "created event[" . get_class($event) . "/$event_path] in module[$root_path]");

				$task_manager->pushEvent( $event );

				$loaded_events[] = $event;
			}
		}

//		log_info( "system,debug", "module", "loaded events: " . print_r($loaded_events,true) );

		return $loaded_events;
	}

}

