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
     * check if action will be processed
     *
     * @param string $action
     *
     * @return bool
     */
    public function isValidAction( $action )
    {
        switch( $action ){
        case "form_token1":
        case "form_token2":
        case "form_token3":
            return TRUE;
        }
        return FALSE;
    }

    /**
     * setup test
     *
     * @param string $action
     * @param Charcoal_IEventContext $context
     */
    public function setUp( $action, $context )
    {

    }

    /**
     * clean up test
     *
     * @param string $action
     * @param Charcoal_IEventContext $context
     */
    public function cleanUp( $action, $context )
    {
    }

    /**
     * execute tests
     *
     * @param string $action
     * @param Charcoal_IEventContext $context
     */
    public function test( $action, $context )
    {
        $session = $context->getSession();
        
        $action = us($action);

        // form token component
        /** @var Charcoal_FormTokenComponent $form_token */
        $form_token = $context->getComponent( 'form_token@:charcoal:form' );

        $config = array();

        $config['token_key'] = 'foo';

        $form_token->configure( $config );

        switch( $action ){
        case "form_token1":
            $token = $form_token->generate( $session );
            echo "token: $token" . PHP_EOL;
            $this->assertNotNull( $token );
            $this->assertNotEmpty( $token );
            break;
        case "form_token2":
            // save my ticket into session
            $token_list   = $session->get( 'token_key' );
            $token_list[] = 'my-ticket';
            $session->set( 'foo', $token_list );
            // validation token will success
            $form_token->validate( $session, 'my-ticket' );
            break;
        case "form_token3":
            // save my ticket into session
            $token_list   = $session->get( 'token_key' );
            $token_list[] = 'my-ticket';
            $session->set( 'foo', $token_list );
            // validation token will fail
            $this->setExpectedException( 'Charcoal_FormTokenValidationException' );
            $form_token->validate( $session, 'another-ticket' );
            break;
        }
    }

}

return __FILE__;