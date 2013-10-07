<?php
/**
* Command Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class TestTask extends Charcoal_Task
{
	/**
	 * execute exception handlers
	 * 
	 * @param Exception $e     exception to handle
	 * 
	 * @return boolean        TRUE means the exception is handled, otherwise FALSE
	 */
	public function handleException( $e )
	{
		if ( $e instanceof Charcoal_CreateObjectException ){
			$path = $e->getObjectPath();
			echo 'illegal object path: ' . $path . PHP_EOL;
		}
		else if ( $e instanceof Charcoal_ObjectPathFormatException ){
			$path = $e->getObjectPath();
			echo 'bad object path: ' . $path . PHP_EOL;
		}

		return TRUE;
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
		$scenario       = $request->getString( 'scenario' );

		if ( $scenario === NULL ){
			echo "actions or scenario parameter must be specified." . eol();
			return TRUE;
		}

		$scenario_file = is_file($scenario) ? $scenario : Charcoal_ResourceLocator::getApplicationFile( 'scenario', $scenario . '.scenario.ini' );
		if ( !is_file($scenario_file) ){
			echo "scenario file not found: $scenario_file" . eol();
			return TRUE;
		}
		$scenario_data = parse_ini_file( $scenario_file, TRUE );

		if ( empty($scenario_data) ){
			echo "couldn't read scenario file: $scenario_file" . eol();
			return TRUE;
		}

		$task_manager = $context->getTaskManager();

		$events = array();
		foreach( $scenario_data as $section => $data ){

			$target = isset($data['target']) ? $data['target'] : NULL;
			$actions = isset($data['actions']) ? $data['actions'] : NULL;

			if ( empty($target) ){
				echo "'target' is not found at section[$section]" . eol();
				return TRUE;
			}
			if ( empty($actions) ){
				echo "'actions' is not found at section[$section]" . eol();
				return TRUE;
			}

			$target_path = new Charcoal_ObjectPath( $target );
			$module_path = '@' . $target_path->getVirtualPath();
			Charcoal_ModuleLoader::loadModule( $this->getSandbox(), $module_path, $task_manager );

			$event_args = array( $section, $target, $actions );
			$events[] = $context->createEvent( 'test', $event_args );
		}

		return $events;
	}
}

return __FILE__;