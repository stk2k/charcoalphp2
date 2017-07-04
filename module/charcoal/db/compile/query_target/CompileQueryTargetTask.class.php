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

class CompileQueryTargetTask extends Charcoal_Task
{
    /**
     * process event
     *
     * @param Charcoal_IEventContext $context
     *
     * @return boolean|Charcoal_Boolean
     */
    public function processEvent( $context )
    {
        /** @var CompileQueryTargetEvent $event */
        $event   = $context->getEvent();

        // get event parameters
        $query_target  = $event->getQueryTarget();
    
        echo 'query_target: ' . print_r($query_target,true) . PHP_EOL;
    
        $query_target = new Charcoal_QueryTarget( $query_target );
        
        echo 'compiled: ' . $query_target . PHP_EOL;

        return b(true);
    }

}

return __FILE__;