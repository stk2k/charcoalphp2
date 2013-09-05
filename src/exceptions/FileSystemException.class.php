<?php
/**
* Exception caused by failure in manipulating file
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_FileSystemException extends Charcoal_RuntimeException
{
	public function __construct( $operation, $reason = NULL, $prev = NULL )
	{
		parent::__construct( "file system operation failed: [operation]$operation [reason]$reason", $prev );
	}
}


