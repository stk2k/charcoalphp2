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


class SmartGatewaySessionHandlerTestTask extends Charcoal_TestTask
{
    private $gw;
    private $handler;

    /**
     * check if action will be processed
     */
    public function isValidAction( $action )
    {
        switch( $action ){
        case "open":
        case "close":
        case "read":
        case "write":
        case "destroy":
        case "gc":
            return TRUE;
        }
        return FALSE;
    }

    /**
     * セットアップ
     */
    public function setUp( $action, $context )
    {
        $action = us($action);

        // SmartGateway
        $this->gw = $context->getComponent( s('smart_gateway@:charcoal:db') );

        $this->handler = Charcoal_Factory::createObject( s('smart_gateway'), s('session_handler') );

        $data = array(
                'target' => 'session',
            );

        $config = new Charcoal_Config( $this->getSandbox()->getEnvironment(), $data );

        $this->handler->configure( $config );

        switch( $action ){

        case "open":
        case "close":
        case "read":
        case "write":
        case "destroy":
        case "gc":
            {
            }
            break;
        default:
            break;
        }

    }

    /**
     * クリーンアップ
     */
    public function cleanUp( $action, $context )
    {
        $action = us($action);

        switch( $action ){

        case "open":
        case "close":
        case "read":
        case "write":
        case "destroy":
        case "gc":
            {
            }
            break;
        default:
            break;
        }
    }

    /**
     * テスト
     */
    public function test( $action, $context )
    {
        $action = us($action);

        switch( $action ){

        case "open":
            $this->handler->open( '/foo/bar', 'test' );

            $save_path = Charcoal_System::getObjectVar( $this->handler, 'save_path' );
            $session_name = Charcoal_System::getObjectVar( $this->handler, 'session_name' );

            $this->assertEquals( '/foo/bar', $save_path );
            $this->assertEquals( 'test', $session_name );

            return TRUE;

        case "close":
            return TRUE;

        case "read":
            return TRUE;

        case "write":
            $id = Charcoal_System::hash();
            $sess_data = 'test session data';

            $this->handler->open( '/foo/bar', 'test' );

            $this->handler->write( $id, $sess_data );

            $criteria = new Charcoal_SQLCriteria( s('session_id = ?'), v(array($id)) );

            $dto = $this->gw->findFirst( qt('session'), $criteria );

            $this->assertEquals( $sess_data, $dto->session_data );
            $this->assertEquals( 'test', $dto->session_name );

            return TRUE;

        case "destroy":
            return TRUE;

        case "gc":
            return TRUE;
        }

        return FALSE;
    }

}

return __FILE__;