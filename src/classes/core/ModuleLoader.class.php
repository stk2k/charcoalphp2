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
	/*
	 * load module files
	 */
	public static function loadModule( Charcoal_ObjectPath $module_path, Charcoal_ITaskManager $task_manager )
	{
		try{
			// create module object
			$module = Charcoal_Factory::createObject( s($module_path->toString()), s('module'), s('Charcoal_IModule'), s('Charcoal_SimpleModule') );

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
					$module = Charcoal_ModuleLoader::loadModule( $obj_path, $task_manager );
					$loaded_modules[] = $module;
				}
			}

			return $module;
		}
		catch( Exception $ex ){
			_catch( $ex );
			_throw( new Charcoal_ModuleLoaderException( s('loadModule'), $ex ) );
		}
	}


}

