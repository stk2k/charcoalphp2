<?php
/**
* exception caused by configuration of sandbox profile
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ProfileConfigException extends Charcoal_ConfigException
{
	public function __construct( $entry, $message = NULL, $prev = NULL )
	{
		if ( $message === NULL ){
			$message = 'Something is wrong in profile config';
		}
		parent::__construct( "[entry]$entry [message]$message", $prev );
	}
}

