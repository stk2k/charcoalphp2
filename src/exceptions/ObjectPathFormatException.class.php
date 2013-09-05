<?php
/**
* exception caused by not suitable for object path
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ObjectPathFormatException extends Charcoal_RuntimeException
{
	public function __construct( $object_path, $message = NULL, $prev = NULL )
	{
		parent::__construct( "Bad object pathformat($message): $object_path", $prev );
	}
}

