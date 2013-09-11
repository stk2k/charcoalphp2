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

class CommandTask extends Charcoal_Task
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
		$action       = $request->getString( 'p1' );

		if ( strlen($action) === 0 ){
			echo 'action is needed.' . PHP_EOL;
			return TRUE;
		}

		$proc = $this->getSandbox()->createObject( $action, 'procedure' );
		Charcoal_Framework::pushProcedure( $proc );

		return TRUE;
	}
}

return __FILE__;