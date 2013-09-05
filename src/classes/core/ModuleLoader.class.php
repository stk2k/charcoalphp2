<?php
/**
* loader for module
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ModuleLoader
{
	static private $loaded_paths;

	/**
	 *  check if module is already loaded
	 *  
	 *  @param $module_path      object path of module to check
	 */
	public static function isLoaded( $module_path )
	{
		if ( $module_path instanceof Charcoal_ObjectPath ){
			$module_path = $module_path->toString();
		}

		return isset(self::$loaded_paths[$module_path]);
	}

	/*
	 * load module files
	 */
	public static function loadModule( Charcoal_ObjectPath $module_path, Charcoal_ITaskManager $task_manager )
	{
		try{
			$path_string = $module_path->toString();

			// check if module is already loaded
			if ( isset(self::$loaded_paths[$path_string]) ){
				log_warning( 'system, error, debug', "module[$path_string] is already loaded." );
				return;
			}

			// create module object
			$module = Charcoal_Factory::createObject( s($path_string), s('module'), v(array()), s('Charcoal_IModule'), s('Charcoal_SimpleModule') );

			// load module tasks
			$module->loadTasks( $task_manager );

			// load module events
			$module->loadEvents( $task_manager );

			// load required modules
			$required_modules = $module->getRequiredModules();
			if ( $required_modules ){
				$loaded_modules = NULL;
				foreach( $required_modules as $module_name ){
					$obj_path = new Charcoal_ObjectPath( s($module_name) );
					self::loadModule( $obj_path, $task_manager );
				}
			}

			self::$loaded_paths[$path_string] = $module_path;
		}
		catch( Exception $ex ){
			_catch( $ex );
			_throw( new Charcoal_ModuleLoaderException( 'loadModule', $ex ) );
		}
	}


}

