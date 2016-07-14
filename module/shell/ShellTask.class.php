<?php
/**
* Command Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class ShellTask extends Charcoal_Task
{
    /**
     * process event
     *
     * @param Charcoal_IEventContext $context   event context
     *
     * @return boolean
     */
    public function processEvent( $context )
    {
        $request   = $context->getRequest();
//        $response  = $context->getResponse();
//        $sequence  = $context->getSequence();
//        $procedure = $context->getProcedure();

        // get paramter from command line
        $target_module       = $request->getString( 'p1' );

        if ( strlen($target_module) === 0 ){
            echo 'target_module is needed.' . PHP_EOL;
            echo 'charcoal [target_module] [param1] [param2]...' . PHP_EOL;
            return TRUE;
        }

        $context->loadModule( $target_module );

        // create shell_command event and push it into the event queue
        /** @var Charcoal_IEvent $event */
        $event = $context->createEvent( 'shell_command' );
        $context->pushEvent( $event );

        return b(true);
    }
}

return __FILE__;