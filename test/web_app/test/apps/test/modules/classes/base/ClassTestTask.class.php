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
	public function isValidAction( Charcoal_String $action )
	{
		switch( $action ){
		case "new_instance":
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * setup test
	 */
	public function setUp( Charcoal_String $action )
	{

	}

	/**
	 * clean up test
	 */
	public function cleanUp( Charcoal_String $action )
	{
	}

	/**
	 * execute tests
	 */
	public function test( Charcoal_String $action, Charcoal_IEventContext $context )
	{
		$action = us($action);

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