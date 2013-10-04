<?php
/**
* exception caused by program logic
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_LogicException extends Charcoal_CharcoalException
{
	public function __construct( $msg, $prev = NULL )
	{
		parent::__construct( $msg, $prev );
	}

}

