<?php
/**
* exception caused by failure in processing event at task
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ProcessEventAtTaskException extends Charcoal_RuntimeException
{
    public function __construct( $event, $task, $result, $message, $prev = NULL )
    {
        $result = print_r($result, true);
        parent::__construct( "Event processing failed at task. [event]$event [task]$task [result]$result [message]$message", $prev );
    }

}

