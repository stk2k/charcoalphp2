<?php
/**
* Test Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class SimplePieTestTask extends Charcoal_TestTask
{
    /**
     * check if action will be processed
     */
    public function isValidAction( $action )
    {
        switch( $action ){
        case "get_feed":
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
        $request   = $context->getRequest();

        $action = us($action);

        // SimplePie
        $simplepie = $context->getComponent( 'simplepie@:rss:simplepie' );

        $config = new Charcoal_Config( $this->getSandbox()->getEnvironment() );

        $config->set( 'enable_cahche', true );
        $config->set( 'cache_dir', CHARCOAL_CACHE_DIR . '/simplepie' );
        $config->set( 'duration', 1800 );

        $simplepie->configure( $config );

        switch( $action ){
        // Send mail
        case "get_feed":
            //$feed      = $simplepie->getFeed( 'http://charcoalphp.org/test/rss/index.xml' );
            $feed      = $simplepie->getFeed( 'http://1000mg.jp/feed' );

            ad( $feed );

            return TRUE;
        }

        return FALSE;
    }

}

return __FILE__;