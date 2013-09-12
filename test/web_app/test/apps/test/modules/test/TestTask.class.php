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
		$target_module       = $request->getString( 'p1' );

		if ( strlen($target_module) === 0 ){
			echo 'target_module is needed.' . PHP_EOL;
			echo 'charcoal [target_module] [test_event]' . PHP_EOL;
			return TRUE;
		}

		$task_manager = $context->getTaskManager();
		Charcoal_ModuleLoader::loadModule( $this->getSandbox(), $target_module, $task_manager );

		return 'test';
	}
}

return __FILE__;