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

class ShowTableCommandTask extends Charcoal_Task
{
    const DIR_MODE     = '666';

    /**
     * イベントを処理する
     *
     * @param Charcoal_IEventContext $context
     *
     * @return boolean
     */
    public function processEvent( $context )
    {
        $request   = $context->getRequest();

        // パラメータを取得
        $database      = us( $request->getString( 'p2' ) );
        $table         = us( $request->getString( 'p3' ) );

        //=======================================
        // Confirm input parameters
        //=======================================
        if ( !empty($database) && !preg_match('/^[0-9a-zA-Z_\-]*$/', $database) ){
            print "Parameter 2(database name) is wrong: $database" . PHP_EOL;
            return b(true);
        }

        if ( !empty($table) && !preg_match('/^[0-9a-zA-Z_\-]*$/', $table) ){
            print "Parameter 3(table name) is wrong: $table" . PHP_EOL;
            return b(true);
        }

        //=======================================
        // Send new project event
        //=======================================
        /** @var Charcoal_IEvent $event */

        $event_path = 'show_table_event@:charcoal:db:show:table';
        $event = $context->createEvent( $event_path, array($database, $table) );
        $context->pushEvent( $event );

        return b(true);
    }
}

return __FILE__;