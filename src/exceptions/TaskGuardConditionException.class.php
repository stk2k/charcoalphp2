<?php
/**
* タスクガード条件例外
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_TaskGuardConditionException extends Charcoal_RuntimeException
{
	public function __construct( ITask $task, ITask $guard_task, Charcoal_String $field, Charcoal_String $message, Exception $previous = NULL )
	{
		$msg  = " [task] $task [guard task] $guard_task [field] $field [message] $message";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}
}

return __FILE__;