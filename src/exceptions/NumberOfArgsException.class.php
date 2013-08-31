<?php
/**
* 引数例外
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_NumberOfArgsException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $function, Charcoal_Vector $args = NULL, Exception $previous = NULL )
	{
		if ( !$args ){
			$msg  = " [function] $function";
		}
		else{
			$msg  = " [function] $function [args] " . implode( ',', $args->toArray() );
		}

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}
}

