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
	 * handle an exception
	 * 
	 * @param Exception $e                        exception to handle
	 * @param Charcoal_EventContext $context      event context
	 *
	 * @return boolean
	 */
	public function handleException( $e, $context )
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
	 *
	 * @return Charcoal_Boolean|bool
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

		foreach( $scenario_data as $section => $data ){

			$target = isset($data['target']) ? $data['target'] : NULL;
			$actions = isset($data['actions']) ? $data['actions'] : NULL;

			log_debug( "debug,scenario", "target: $target" );
			log_debug( "debug,scenario", "actions: $actions" );

			if ( empty($target) ){
				echo "[WARNING] 'target' is not found at section[$section]" . eol();
				log_warning( "debug, scenario", "'target' is not found at section[$section]" );
				continue;
			}
			if ( empty($actions) ){
				echo "[WARNING] 'actions' is not found at section[$section]" . eol();
				log_warning( "debug, scenario", "'actions' is not found at section[$section]" );
				continue;
			}

			$target_path = new Charcoal_ObjectPath( $target );
			$module_path = '@' . $target_path->getVirtualPath();
			$context->loadModule( $module_path );
			log_info( "debug,scenario", "loaded module: $module_path" );

			$event_args = array( $section, $target, $actions );
			/** @var Charcoal_IEvent $event */
			$event = $context->createEvent( 'test', $event_args );
			$context->pushEvent( $event );
			log_debug( "debug,scenario", "event_args: " . print_r($event_args,true) );
			log_debug( "debug,scenario", "pushed event: " . print_r($event,true) );
		}

		return TRUE;
	}
}

return __FILE__;