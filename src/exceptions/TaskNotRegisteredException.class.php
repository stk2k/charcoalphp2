<?php
/**
* タスク未登録例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_TaskNotRegisteredException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $task_name, Charcoal_String $message = NULL, Exception $previous = NULL )
	{
		$msg  = " [task name] $task_name";
		if ( $message ){
			$msg .= " [message]$message";
		}

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}
}


return __FILE__;