<?php
/**
* exception caused by failure in processing event
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ProcessEventException extends Charcoal_RuntimeException
{
	public function __construct( $event, $task, $result, $message, $prev = NULL )
	{
		parent::__construct( "Event processing failed. [event]$event [task]$task [result]$result [message]$message", $prev );
	}

}

