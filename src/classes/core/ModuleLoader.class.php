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
	 * 
	 * @param Charcoal_Sandbox $sandbox                  sandbox object
	 * @param CharcCharcoal_ObjectPath $module_path      module path to create
	 * @param Charcoal_ITaskManager $task_manager        task manager
	 */
	public static function loadModule( $sandbox, $module_path, $task_manager )
	{
		Charcoal_ParamTrait::checkSandbox( 1, $sandbox );
		Charcoal_ParamTrait::checkStringOrObjectPath( 2, $module_path );
		Charcoal_ParamTrait::checkImplements( 3, 'Charcoal_ITaskManager', $task_manager );

		try{
			log_debug( 'debug, event', "loading module: $module_path" );

			if ( $module_path instanceof Charcoal_ObjectPath ){
				$module_path = $module_path->toString();
			}
			else{
				$module_path = us( $module_path );
			}

			// check if module is already loaded
			if ( isset(self::$loaded_paths[$module_path]) ){
				log_warning( 'system, warning, debug', "module[$module_path] is already loaded." );
				return;
			}

			// create module object
			$module = $sandbox->createObject( $module_path, 'module', array(), 'Charcoal_IModule', 'Charcoal_SimpleModule' );

			// load module tasks
			$module->loadTasks( $task_manager );

			// load module events
			$module->loadEvents( $task_manager );

			// load required modules
			$required_modules = $module->getRequiredModules();
			if ( $required_modules ){
				$loaded_modules = NULL;
				foreach( $required_modules as $module_name ){
					if ( strlen($module_name) === 0 )    continue;

					self::loadModule( $module_name, $task_manager );
				}
			}

			self::$loaded_paths[$module_path] = $module_path;
		}
		catch( Exception $ex ){
			_catch( $ex );
			_throw( new Charcoal_ModuleLoaderException( "failed to load  module: [$module_path]", $ex ) );
		}
	}


}

