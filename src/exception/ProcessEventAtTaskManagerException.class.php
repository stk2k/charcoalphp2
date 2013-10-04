<?php
/**
* exception caused by failure in processing event at task manager
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ProcessEventAtTaskManagerException extends Charcoal_RuntimeException
{
	public function __construct( $prev = NULL )
	{
		parent::__construct( 'Event processing failed at task manager.', $prev );
	}

}

