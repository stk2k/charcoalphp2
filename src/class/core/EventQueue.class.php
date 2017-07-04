<?php
/**
* イベントのキューを扱うクラス
*
* PHP version 5
*
* @package    class.core
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_EventQueue extends Charcoal_Queue implements Charcoal_IEventQueue
{
    /**
     * dump events
     */
    public function dumpEvents()
    {
        $array = $this->getValue();

        if ( !$array ){
            return;
        }

        foreach ( $array as $key => $event ){
            /** @var Charcoal_Event $event */
            $event_name = $event->getObjectName();
            echo $event_name . PHP_EOL;
        }
    }
}

