<?php
/**
* スタック空例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_EmptyStackException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_Stack $stack, Exception $previous = NULL )
	{
		$msg = "stack empty($stack)";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}

