<?php
/**
* 基本的なモジュール実装
*
* PHP version 5
*
* @package    procedures
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
	public function configure( Charcoal_Config $config )
	{
		$this->_tasks              = $config->getArray( s('tasks'), v(array()) );
		$this->_events             = $config->getArray( s('events'), v(array()) );
		$this->_required_modules   = $config->getArray( s('required_modules'), v(array()) );

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
	private function loadTask( Charcoal_ObjectPath $obj_path, $file, Charcoal_ITaskManager $task_manager )
	{
		// ソースを取り込む
		if ( !is_readable($file) ){
//			log_warning( "system,debug,include,task", "module", "task class file not readable:" . $file );
			return NULL;
		}

		// file base name
		$base_name = basename($file);

		// retrieve class name from file name
		$pos = strpos($base_name,CHARCOAL_CLASS_FILE_SUFFIX);
		if ( $pos === FALSE ){
//			log_warning( "system,debug,include,task", "module", "file name does not meet framework contract:" . $file );
			return NULL;
		}

		$class_name = substr($base_name,0,$pos);

		// include source file
		Charcoal_Framework::loadSourceFile( new Charcoal_File(s($file)) );
//		log_debug( "system,debug,include,task", "module", "require_once:" . $file );

		// create new instance
		$klass = new Charcoal_Class( s($class_name) );
		$task = $klass->newInstance();

//		log_info( "system,debug,task", "module", "created task[$task] in module[$obj_path]");

		// build object path for the task
		$obj_name = $task->getObjectName();
		$task_path = s($obj_name . '@' . $obj_path->getVirtualPath());
		$task_path = new Charcoal_ObjectPath(s($task_path));
//		log_info( "system,debug,task", "module", "task[$task] path: [$task_path]");

		// set task property
		$task->setObjectPath( $task_path );
		$task->setTypeName( s('task') );

		// load object config
		$config = new Charcoal_Config();
		Charcoal_ConfigLoader::loadConfig( $task_path, s('task'), $config );

		// configure task
		$task->configure( $config );
//		log_info( "system,debug,task", "module", "task[$event] configured.");

		// regiser task
		$task_manager->registerTask( s($task_path->toString()), $task );
//		log_info( "system,debug,task", "module", "task[$class_name] registered as: [$task_path]");

		return $task;
	}

	/*
	 * load a rask
	 */
	private function loadEvent( Charcoal_ObjectPath $obj_path, $file, Charcoal_ITaskManager $task_manager )
	{
		// ソースを取り込む
		if ( !is_readable($file) ){
//			log_warning( "system,debug,include,event", "module", "event class file not readable:" . $file );
			return NULL;
		}

		// file base name
		$base_name = basename($file);

		// retrieve class name from file name
		$pos = strpos($base_name,CHARCOAL_CLASS_FILE_SUFFIX);
		if ( $pos === FALSE ){
//			log_warning( "system,debug,include,event", "module", "file name does not meet framework contract:" . $file );
			return NULL;
		}

		$class_name = substr($base_name,0,$pos);

		// include source file
		Charcoal_Framework::loadSourceFile( new Charcoal_File(s($file)) );
//		log_debug( "system,debug,include,event", "module", "require_once:" . $file );

		// create new instance
		$klass = new Charcoal_Class( s($class_name) );
		$event = $klass->newInstance();

//		log_info( "system,debug,event", "module", "created event[$event] in module[$obj_path]");

		// build object path for the event
		$obj_name = $event->getObjectName();
		$event_path = $obj_name . '@' . $obj_path->getVirtualPath();
		$event_path = new Charcoal_ObjectPath(s($event_path));
//		log_info( "system,debug,event", "module", "event[$event] path: [$event_path]");

		// set task property
		$event->setObjectPath( $event_path );
		$event->setTypeName( s('event') );

		// load object config
		$config = new Charcoal_Config();
		Charcoal_ConfigLoader::loadConfig( $event_path, s('event'), $config );

		// configure event
		$event->configure( $config );
//		log_info( "system,debug,event", "module", "event[$event] configured.");

		// add event 
		$task_manager->pushEvent( $event );
//		log_info( "system,debug,event", "module", "event[$event] added to task manager.");

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

		$webapp_path    = Charcoal_ResourceLocator::getApplicationPath( s('modules' . $real_path) );
		$project_path   = Charcoal_ResourceLocator::getProjectPath( s('modules' . $real_path) );
		$framework_path = Charcoal_ResourceLocator::getFrameworkPath( s('modules' . $real_path) );

		$task_class_suffix = 'Task' . CHARCOAL_CLASS_FILE_SUFFIX;

		if ( $dh = opendir($webapp_path) )
		{
			while( ($file = readdir($dh)) !== FALSE )
			{
				if ( strpos($file,$task_class_suffix) !== FALSE ){
					$task = $this->loadTask( $root_path, "$webapp_path/$file", $task_manager );
					if ( $task ){
						$loaded_tasks[] = $task;
					}
				}
			}
			closedir($dh);
		}

		if ( $dh = opendir($project_path) )
		{
			while( ($file = readdir($dh)) !== FALSE )
			{
				if ( strpos($file,$task_class_suffix) !== FALSE ){
					$task = $this->loadTask( $root_path, "$project_path/$file", $task_manager );
					if ( $task ){
						$loaded_tasks[] = $task;
					}
				}
			}
			closedir($dh);
		}

		if ( $dh = opendir($framework_path) )
		{
			while( ($file = readdir($dh)) !== FALSE )
			{
				if ( strpos($file,$task_class_suffix) !== FALSE ){
					$task = $this->loadTask( $root_path, "$framework_path/$file", $task_manager );
					if ( $task ){
						$loaded_tasks[] = $task;
					}
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

				$task = Charcoal_Factory::createObject( s($task_path), s('task') );

//				log_info( "system,debug", "module", "created task[" . get_class($task) . "/$task_path] in module[$root_path]");

				$task_manager->registerTask( $task_path, $task );

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

		$webapp_path    = Charcoal_ResourceLocator::getApplicationPath( s('modules' . $real_path) );
		$project_path   = Charcoal_ResourceLocator::getProjectPath( s('modules' . $real_path) );
		$framework_path = Charcoal_ResourceLocator::getFrameworkPath( s('modules' . $real_path) );

		$event_class_suffix = 'Event' . CHARCOAL_CLASS_FILE_SUFFIX;

		if ( $dh = opendir($webapp_path) )
		{
			while( ($file = readdir($dh)) !== FALSE )
			{
				if ( strpos($file,$event_class_suffix) !== FALSE ){
					$task = $this->loadEvent( $root_path, "$webapp_path/$file", $task_manager );
					if ( $task ){
						$loaded_events[] = $task;
					}
				}
			}
			closedir($dh);
		}

		if ( $dh = opendir($project_path) )
		{
			while( ($file = readdir($dh)) !== FALSE )
			{
				if ( strpos($file,$event_class_suffix) !== FALSE ){
					$task = $this->loadEvent( $root_path, "$project_path/$file", $task_manager );
					if ( $task ){
						$loaded_events[] = $task;
					}
				}
			}
			closedir($dh);
		}

		if ( $dh = opendir($framework_path) )
		{
			while( ($file = readdir($dh)) !== FALSE )
			{
				if ( strpos($file,$event_class_suffix) !== FALSE ){
					$task = $this->loadEvent( $root_path, "$framework_path/$file", $task_manager );
					if ( $task ){
						$loaded_events[] = $task;
					}
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

				$event = Charcoal_Factory::createObject( s($event_path), s('event') );

//				log_info( "system,debug", "module", "created event[" . get_class($event) . "/$event_path] in module[$root_path]");

				$task_manager->pushEvent( $event );

				$loaded_events[] = $event;
			}
		}

//		log_info( "system,debug", "module", "loaded events: " . print_r($loaded_events,true) );

		return $loaded_events;
	}

}

