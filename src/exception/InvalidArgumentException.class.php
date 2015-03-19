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
	public function __construct( $argument, $prev = NULL )
	{
		Charcoal_ParamTrait::validateString( 1, $argument );
		Charcoal_ParamTrait::validateException( 2, $prev, TRUE );

		parent::__construct( "Invalid argument: $argument", $prev );
	}

}


