<?php
/**
* イベント処理例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ProcessEventException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_IEvent $event, Charcoal_ITask $task, $result, Charcoal_String $message, Exception $previous = NULL )
	{
		$msg = " [event]$event [task]$task [result]$result [message]$message";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}

