<?php
/**
* Basic event class
*
* PHP version 5
*
* @package    objects.events
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

abstract class Charcoal_Event extends Charcoal_CharcoalComponent implements Charcoal_IEvent
{
    const EXIT_CODE_OK            = 0;
    const EXIT_CODE_ABORT         = 1;

    const ABORT_TYPE_IMMEDIATELY      = 0;        // このイベント直後にイベント処理停止
    const ABORT_TYPE_AFTER_THIS_LOOP  = 1;        // このイベントループ終了後にイベント処理停止

}

