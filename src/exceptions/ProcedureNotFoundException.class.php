<?php
/**
* exception caused by failure in finding procudure
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ProcedureNotFoundException extends Charcoal_ConfigException
{
	public function __construct( Charcoal_String $proc_path, $prev = NULL )
	{
		parent::__construct( "Procedure Not Found: [$proc_path].", $prev );
	}
}


