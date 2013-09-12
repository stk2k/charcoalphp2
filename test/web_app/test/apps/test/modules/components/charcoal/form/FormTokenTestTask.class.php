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

class FormTokenTestTask extends Charcoal_TestTask
{
	/**
	 * setup test
	 */
	public function setUp()
	{

	}

	/**
	 * clean up test
	 */
	public function cleanUp()
	{
	}

	/**
	 * execute tests
	 */
	public function test( $action, $context )
	{
		$action = us($action);

		$sequence  = new Charcoal_SequenceHolder( new Charcoal_Sequence(), new Charcoal_Sequence() );

		// form token component
		$form_token = Charcoal_DIContainer::getComponent( s('form_token@:charcoal:form') );

		$config = new Charcoal_Config();

		$config->set( s('token_key'), 'foo' );

		$form_token->configure( $config );

		switch( $action ){
		case "form_token1":
			$token = $form_token->generate( $sequence );
			echo "token: $token" . PHP_EOL;
			$this->assertNotNull( $token );
			$this->assertNotEmpty( $token );
			break;
		case "form_token2":
			// save my ticket into sequence 
			$token_list   = $sequence->get( s('token_key') );
			$token_list[] = 'my-ticket';
			$sequence->set( s('foo'), $token_list );
			// validation token will success
			$form_token->validate( $sequence, s('my-ticket') );
			break;
		case "form_token3":
			// save my ticket into sequence 
			$token_list   = $sequence->get( s('token_key') );
			$token_list[] = 'my-ticket';
			$sequence->set( s('foo'), $token_list );
			// validation token will fail
			$this->setExpectedException( s('Charcoal_FormTokenValidationException') );
			$form_token->validate( $sequence, s('another-ticket') );
			break;
		}
	}

}

return __FILE__;