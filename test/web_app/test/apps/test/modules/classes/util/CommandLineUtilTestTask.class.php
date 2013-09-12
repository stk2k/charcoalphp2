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

class CommandLineUtilTestTask extends Charcoal_TestTask
{
	/**
	 * check if action will be processed
	 */
	public function isValidAction( $action )
	{
		switch( $action ){
		case "split_params1":
		case "split_params2":
		case "split_params3":
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * setup test
	 */
	public function setUp( $action )
	{

	}

	/**
	 * clean up test
	 */
	public function cleanUp( $action )
	{
	}

	/**
	 * execute tests
	 */
	public function test( $action, $context )
	{
		$action = us($action);

		switch( $action ){
		case "split_params1":

			$args_commandline = "foo bar baz";

			$actual = Charcoal_CommandLineUtil::splitParams( s($args_commandline) );

			$extected = array( "foo", "bar", "baz" );

			$this->assertEquals( $extected, $actual );

			return TRUE;

		case "split_params2":

			$args_commandline = "foo\\'s bar\\'s 'baz'";

			$actual = Charcoal_CommandLineUtil::splitParams( s($args_commandline) );

			$extected = array( "foo's", "bar's", "baz" );

			$this->assertEquals( $extected, $actual );

			return TRUE;


		case "split_params3":

			$args_commandline = "'Teacher\\'s Voice' \"Teacher\\'s Voice\" 'Teacher\\\"s Voice'";

			$actual = Charcoal_CommandLineUtil::splitParams( s($args_commandline) );

print_r($actual);


			$extected = array( "Teacher's Voice", "Teacher's Voice", "Teacher\"s Voice" );

			$this->assertEquals( $extected, $actual );

			return TRUE;
		}

		return FALSE;
	}

}

return __FILE__;