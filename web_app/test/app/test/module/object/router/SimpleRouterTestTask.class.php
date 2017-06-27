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

class SimpleRouterTestTask extends Charcoal_TestTask
{
    /**
     * check if action will be processed
     */
    public function isValidAction( $action )
    {
        switch( $action ){
        case "no_param":
        case "one_param":
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

        $simple_router = $context->createObject( 'simple_router', 'router', 'Charcoal_IRouter' );
        $routing_rule = $context->createObject( 'array', 'routing_rule', 'Charcoal_IRoutingRule' );

        $config = array(
                'routing rules' => array(
                        '/path/to/check/' => '@:path:to:check',
                        '/path/to/check/:param' => '@:path:to:check'
                    ),
            );

        $routing_rule->configure($config);

        $proc_key = $context->getProfile()->getString( 'PROC_KEY', 'proc' );

        switch( $action ){
        case "no_param":

            $request = $context->getRequest();

            $_SERVER["REQUEST_URI"] = '/path/to/check/';
            $_SERVER["SCRIPT_NAME"] = '';

            $result = $simple_router->route( $request, $routing_rule );

            $proc = $request->get( $proc_key );

            $this->assertEquals( "@:path:to:check", $proc );

            break;

        case "one_param":

            $request = $context->getRequest();

            $_SERVER["REQUEST_URI"] = '/path/to/check/1';
            $_SERVER["SCRIPT_NAME"] = '';

            $result = $simple_router->route( $request, $routing_rule );

            $proc = $request->get( $proc_key );

            ad( $result );

            $this->assertEquals( "@:path:to:check", $proc );

            break;
        }
    }

}

return __FILE__;