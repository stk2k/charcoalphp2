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
    /*
     * イベントのキューを優先度でソートする
     *
     */
    public function sortByPriority()
    {
        $array = $this->getValue();

        if ( !$array ){
            return;
        }

        $key_priority = NULL;
        foreach ( $array as $key => $event ){
            $key_priority[$key] = ui( $event->getPriority() );
        }
        array_multisort( $key_priority,SORT_DESC, $array );

        $this->setValue( $array );
    }
}

