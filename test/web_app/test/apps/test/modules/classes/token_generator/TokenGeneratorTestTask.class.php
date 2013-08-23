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
		case "simple_default":
			// create token generator object
			$generator = Charcoal_Factory::createObject( s('simple'), s('token_generator') );

			$token = $generator->generateToken();

			$this->assertEquals( strlen($token), 40 );
			$this->assertEquals( preg_match("/[^0-9a-zA-Z]+/", $token), false );

			echo "default token: $token";
			break;
		case "simple_sha1":
			// create token generator object
			$generator = Charcoal_Factory::createObject( s('simple'), s('token_generator') );

			$config = new Charcoal_Config();
			$config->set( s('algorithm'), 'sha1' );
			$generator->configure( $config );

			$token = $generator->generateToken();

			$this->assertEquals( strlen($token), 40 );
			$this->assertEquals( preg_match("/[^0-9a-zA-Z]+/", $token), false );

			echo "sha1 token: $token";
			break;
		case "simple_md5":
			// create token generator object
			$generator = Charcoal_Factory::createObject( s('simple'), s('token_generator') );

			$config = new Charcoal_Config();
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