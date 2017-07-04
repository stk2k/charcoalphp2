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

class CompileQueryTargetCommandTask extends Charcoal_Task
{
    /**
     * イベントを処理する
     *
     * @param Charcoal_IEventContext $context
     *
     * @return boolean|Charcoal_Boolean
     */
    public function processEvent( $context )
    {
        $request   = $context->getRequest();

        // パラメータを取得
        $query_target  = us( $request->getString( 'p2' ) );

        //=======================================
        // Send new project event
        //=======================================
        /** @var Charcoal_IEvent $event */

        $event_path = 'compile_query_target_event@:charcoal:db:compile:query_target';
        $event = $context->createEvent( $event_path, array($query_target) );
        $context->pushEvent( $event );

        return b(true);
    }
}

return __FILE__;