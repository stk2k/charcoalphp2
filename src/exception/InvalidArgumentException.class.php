<?php
/**
* Interface is not found exception 
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_InvalidArgumentException extends Charcoal_RuntimeException
{
	public function __construct( $arg, $prev = NULL )
	{
		$expr = method_exists($arg,'_toString') ? "$arg" : gettype($arg) . '#' . spl_object_hash($arg);

		parent::__construct( "Invalid argument: $expr", $prev );
	}

}


