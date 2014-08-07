<?php
/**
* Command Task
*
* PHP version 5
*
* @package	renderers
* @author	 stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class TestDispatcherTask extends Charcoal_Task
{
	private $scenario_dir;

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );
		
		$this->setPostActions( array('remove_event', 'remove_task') );

		$this->scenario_dir  = $config->getString( 'scenario_dir', Charcoal_ResourceLocator::getApplicationPath('scenario'), TRUE );

		if ( $this->getSandbox()->isDebug() ){
			log_debug( "debug,event", "Task[$this] post_actions: " . $this->getPostActions(), "post_actions" );
			log_debug( "debug,event", "scenario_dir: {$this->scenario_dir}" );
		}
	}

	/**
	 * execute exception handlers
	 * 
	 * @param Exception $e	 exception to handle
	 * 
	 * @return boolean		TRUE means the exception is handled, otherwise FALSE
	 */
	public function handleException( $e )
	{
		$ret = TRUE;

		if ( $e instanceof Charcoal_CreateObjectException ){
			echo 'illegal object path: ' . $e->getObjectPath() . PHP_EOL;
		}
		else if ( $e instanceof Charcoal_ObjectPathFormatException ){
			echo 'bad object path format: ' . $e->getObjectPath() . PHP_EOL;
		}
		else if ( $e instanceof Charcoal_ModuleLoaderException ){
			echo 'module not found: ' . $e->getModulePath() . PHP_EOL;
		}
		else{
			$ret = FALSE;
		}

		return $ret;
	}

	/**
	 * process event
	 *
	 * @param Charcoal_IEventContext $context   event context
	 */
	public function processEvent( $context )
	{
		$request   = $context->getRequest();
//		$response  = $context->getResponse();
//		$sequence  = $context->getSequence();
//		$procedure = $context->getProcedure();

		// get paramter from command line
		$scenario	   = $request->getString( 'scenario' );

		$scenario = trim($scenario);
		log_debug( "debug,scenario", "scenario: $scenario" );

		if ( $scenario === NULL ){
			echo "actions or scenario parameter must be specified." . eol();
			log_error( "debug,error,scenario", "actions or scenario parameter must be specified." );
			return TRUE;
		}

		$scenario_file = $this->scenario_dir . '/' . $scenario . '.scenario.ini';
		if ( !is_file($scenario_file) ){
			echo "scenario file not found: $scenario_file" . eol();
			log_error( "debug,error,scenario", "scenario file not found: $scenario_file" );
			return TRUE;
		}
		$scenario_data = parse_ini_file( $scenario_file, TRUE );
		log_debug( "debug,scenario", "scenario_data: " . print_r($scenario_data,true) );

		if ( empty($scenario_data) ){
			echo "couldn't read scenario file: $scenario_file" . eol();
			log_error( "debug,error,scenario", "couldn't read scenario file: $scenario_file" );
			return TRUE;
		}

		$task_manager = $context->getTaskManager();

		$events = array();
		foreach( $scenario_data as $section => $data ){

			$target = isset($data['target']) ? $data['target'] : NULL;
			$actions = isset($data['actions']) ? $data['actions'] : NULL;

			log_debug( "debug,scenario", "target: $target" );
			log_debug( "debug,scenario", "actions: $actions" );

			if ( empty($target) ){
				echo "'target' is not found at section[$section]" . eol();
				log_error( "debug,error,scenario", "'target' is not found at section[$section]" );
				return TRUE;
			}
			if ( empty($actions) ){
				echo "'actions' is not found at section[$section]" . eol();
				log_error( "debug,error,scenario", "'actions' is not found at section[$section]" );
				return TRUE;
			}

			$target_path = new Charcoal_ObjectPath( $target );
			$module_path = '@' . $target_path->getVirtualPath();
			Charcoal_ModuleLoader::loadModule( $this->getSandbox(), $module_path, $task_manager );
			log_info( "debug,scenario", "loaded module: $module_path" );

			$event_args = array( $section, $target, $actions );
			$events[] = $context->createEvent( 'test', $event_args );
			log_debug( "debug,scenario", "event_args: " . print_r($event_args,true) );
			log_debug( "debug,scenario", "events: " . print_r($events,true) );
		}

		return $events;
	}
}

return __FILE__;