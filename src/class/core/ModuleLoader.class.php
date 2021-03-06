<?php
/**
* loader for module
*
* PHP version 5
*
* @package    class.core
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ModuleLoader
{
    static private $loaded_paths;

    /**
     *  check if module is already loaded
     *
     *  @param $module_path      object path of module to check
     *
     * @return Charcoal_Boolean|bool
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
     * @param CharcCharcoal_ObjectPath $module_path      module path to load
     * @param Charcoal_ITaskManager $task_manager        task manager
     */
    public static function loadModule( $sandbox, $module_path, $task_manager )
    {
        /** @var Charcoal_Sandbox $sandbox */
        $event_stream = $sandbox->getCoreHookEventStream();
    
        try{
            log_debug( 'debug, event', "loading module: $module_path" );
            $event_stream->push( 'module.loading', $module_path, true );

            if ( $module_path instanceof Charcoal_ObjectPath ){
                $module_path = $module_path->toString();
            }
            else{
                $module_path = us( $module_path );
            }

            // check if module is already loaded
            if ( isset(self::$loaded_paths[$module_path]) ){
                log_warning( 'system, event, debug', "module[$module_path] is already loaded." );
                return;
            }

            /** @var Charcoal_IModule $module */
            $module = $sandbox->createObject( $module_path, 'module', array(), array(), 'Charcoal_IModule', 'Charcoal_SimpleModule' );

            // load module tasks
            $loaded_tasks = $module->loadTasks( $task_manager );
            $event_stream->push( 'module.loaded_tasks', $loaded_tasks, true );

            // load module events source code
            $loaded_events = $module->loadEvents( $task_manager );
            $event_stream->push( 'module.loaded_events', $loaded_events, true );

            // if no tasks or events are loaded, you maybe passed a wrong module path
            if ( empty($loaded_tasks) && empty($loaded_events) ){
                _throw( new Charcoal_ModuleLoaderException( $module_path, "no tasks and events are loaded." ) );
            }

            // load required modules
            $required_modules = $module->getRequiredModules();
            if ( $required_modules ){
                $loaded_modules = NULL;
                foreach( $required_modules as $module_name ){
                    if ( strlen($module_name) === 0 )    continue;
    
                    $event_stream->push( 'module.loading_required_module', $module_name, true );
                    self::loadModule( $sandbox, $module_name, $task_manager );
                    $event_stream->push( 'module.loaded_required_module', $module_name, true );
                }
            }

            self::$loaded_paths[$module_path] = $module_path;

            log_debug( 'debug, event, module', "loaded module: $module_path" );
            $event_stream->push( 'module.loaded', $module_path, true );

        }
        catch( Exception $ex ){
            _catch( $ex );
            _throw( new Charcoal_ModuleLoaderException( $module_path, "failed to load  module.", $ex ) );
        }
    }


}

