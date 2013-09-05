<?php
/**
* exception caused by failure in creating object
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_CreateObjectException extends Charcoal_RuntimeException
{
	public function __construct( $obj_path, $type_name, $prev = NULL )
	{
		parent::__construct( "Creating Charcoal Object Failed. [obj_path]$obj_path [type_name]$type_name", $prev );
	}

}

