<?php
/**
* Exception while query target parsing
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_QueryTargetException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $expression, Exception $prev = NULL )
	{
		$msg = "Illegal query target format: {$expression}";

		if ( $prev ) parent::__construct( s($msg), $prev ); else parent::__construct( s($msg) );
	}

}
return __FILE__;
