<?php
/**
* プロシージャ例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ProcedureNotFoundException extends Charcoal_ConfigException
{
	public function __construct( Charcoal_String $proc_path, Exception $prev = NULL )
	{
		$msg  = "Procedure Not Found: [$proc_path].";

		if ( $prev ) parent::__construct( s($msg), $prev ); else parent::__construct( s($msg) );
	}
}


