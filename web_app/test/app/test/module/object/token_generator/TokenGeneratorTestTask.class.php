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

class TokenGeneratorTestTask extends Charcoal_TestTask
{
	/**
	 * check if action will be processed
	 */
	public function isValidAction( $action )
	{
		switch( $action ){
		case "simple_default":
		case "simple_sha1":
		case "simple_md5":
			return TRUE;
		}
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

		// create token generator object
		$generator = $context->createObject( 'simple', 'token_generator' );

		switch( $action ){
		case "simple_default":
			// create token generator object
			$token = $generator->generateToken();

			$this->assertEquals( strlen($token), 40 );
			$this->assertEquals( preg_match("/[^0-9a-zA-Z]+/", $token), false );

			echo "default token: $token";
			break;
		case "simple_sha1":
			$config = new Charcoal_Config( $this->getSandbox()->getEnvironment() );
			$config->set( s('algorithm'), 'sha1' );
			$generator->configure( $config );

			$token = $generator->generateToken();

			$this->assertEquals( strlen($token), 40 );
			$this->assertEquals( preg_match("/[^0-9a-zA-Z]+/", $token), false );

			echo "sha1 token: $token";
			break;
		case "simple_md5":
			$config = new Charcoal_Config( $this->getSandbox()->getEnvironment() );
			$config->set( s('algorithm'), 'md5' );
			$generator->configure( $config );

			$token = $generator->generateToken();

			$this->assertEquals( strlen($token), 32 );
			$this->assertEquals( preg_match("/[^0-9a-zA-Z]+/", $token), false );

			echo "md5 token: $token";
			break;
		}
	}

}

return __FILE__;