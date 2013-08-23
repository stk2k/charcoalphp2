<?php
/**
* Exception which means no sections are found in configure file
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ConfigSectionNotFoundException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $section, Exception $prev = NULL )
	{
		$msg = "[section]$section";

		if ( $prev ) parent::__construct( s($msg), $prev ); else parent::__construct( s($msg) );
	}

}

return __FILE__;