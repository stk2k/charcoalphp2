<?php
/**
* Exception caused by failure in manipulating file
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_FilePermissionException extends Charcoal_RuntimeException
{
	const READ_PERMISSION     = 'r';
	const WRITE_PERMISSION    = 'w';
	const EXECUTE_PERMISSION  = 'x';

	public function __construct( $path, $needed = NULL, $prev = NULL )
	{
		$perm = file_exists($path) ? substr(sprintf('%o', fileperms($path)), -4) : '---';
		parent::__construct( "file permission is not enough: $path perm=[$perm] needed=[$needed]", $prev );
	}
}


