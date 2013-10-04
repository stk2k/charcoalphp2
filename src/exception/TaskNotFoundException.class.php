<?php
/**
* exception caused by failure in finding task
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_TaskNotFoundException extends Charcoal_RuntimeException
{
	public function __construct( $task, $prev = NULL )
	{
		parent::__construct( "task not found: $task", $prev );
	}
}


