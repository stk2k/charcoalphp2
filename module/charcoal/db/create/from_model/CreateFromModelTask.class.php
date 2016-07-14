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

class CreateFromModelTask extends Charcoal_Task
{
    const DIR_MODE        = '666';
    const SPACE_COUNT     = 30;

    /**
     * process event
     *
     * @param Charcoal_IEventContext $context
     *
     * @return boolean|Charcoal_Boolean
     */
    public function processEvent( $context )
    {
        /** @var GenerateModelEvent $event */
        $event   = $context->getEvent();

        // get event parameters
        $db_name       = $event->getDatabase();

        /** @var Charcoal_SmartGateway $gw */
        $gw = $context->getComponent( 'smart_gateway@:charcoal:db' );

        return b(true);
    }

}

return __FILE__;