<?php
/**
* システムイベント用マーカークラス
*
* PHP version 5
*
* @package    objects.events
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

abstract class Charcoal_SystemEvent extends Charcoal_Event implements Charcoal_IEvent
{
    /**
     *  ターゲットタスクの一覧を取得
     */
    public function getTargetTaskList()
    {
        return new Charcoal_Vector();
    }

    /**
     *  自動投入するか
     */
    public function isAutoInjected()
    {
        return FALSE;
    }
}

