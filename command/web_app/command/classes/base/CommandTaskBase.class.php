<?php
/**
* タスク
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

abstract class CommandTaskBase extends Charcoal_Task
{
	/**
	 * setup
	 *
	 * @return void
	 */
	public abstract function setUp();

	/**
	 * clean up
	 *
	 * @return void
	 */
	public abstract function cleanUp();

	/**
	 * execute command
	 *
	 * @param Charcoal_String $action   action name to execute
	 */
	public abstract function execute( Charcoal_String $action );

	/**
	 * process event
	 *
	 * @param Charcoal_IEventContext $context   event context
	 */
	public function processEvent( Charcoal_IEventContext $context )
	{
		$request   = $context->getRequest();
		$response  = $context->getResponse();
		$sequence  = $context->getSequence();
		$procedure = $context->getProcedure();

		// get paramter from command line
		$actions       = $request->getString( s("actions") );

		// convert comma separated action list to PHP array
		$action_list = $actions->split( s(',') );

		// initialize
		$commands = 0;

		try{
			$this->setUp();
		}
		catch( Exception $e ){
			print "Command execution failed while setup:" . $e . PHP_EOL;
			return b(true);
		}

		foreach( $action_list as $action ){
			$this->action = $action = trim( $action );
			$tests ++;

			$this->execute( s($action) );
		}

		try{
			$this->cleanUp();
		}
		catch( Exception $e ){
			print "Command execution failed while clean up:" . $e . PHP_EOL;
			return b(true);
		}

		return b(TRUE);
	}
}

return __FILE__;