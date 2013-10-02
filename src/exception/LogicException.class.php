<?php
/**
* exception caused by program logic
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_LogicException extends Charcoal_CharcoalException
{
	public function __construct( $msg, $prev = NULL )
	{
		parent::__construct( $msg, $prev );
	}

}

