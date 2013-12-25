<?php
/**
* Form Test Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class Foo
{
}

class ClassTestTask extends Charcoal_TestTask
{
	/**
	 * check if action will be processed
	 */
	public function isValidAction( $action )
	{
		switch( $action ){
		case "new_instance":
			log_debug( "debug,event", "action is valid: $action" );
			return TRUE;
		}
		log_debug( "debug,event", "action is invalid: $action" );
		return FALSE;
	}

	/**
	 * setup test
	 */
	public function setUp( $action, $context )
	{

	}

	/**
	 * clean up test
	 */
	public function cleanUp( $action, $context )
	{
	}

	/**
	 * execute tests
	 */
	public function test( $action, $context )
	{
		$action = us($action);

		log_debug( "debug,event", "action:$action" );

		switch( $action ){
		case "new_instance":
			$klass = new Charcoal_Class( s('Foo') );

			$object = $klass->newInstance();

			$this->assertEquals( "Foo", get_class($object) );

			return TRUE;
		}

		return FALSE;
	}

}

return __FILE__;