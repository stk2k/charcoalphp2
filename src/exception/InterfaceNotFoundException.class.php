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

class Charcoal_InterfaceNotFoundException extends Charcoal_RuntimeException
{
	public function __construct( $interface_name, $prev = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $interface_name );

		parent::__construct( "Interface not found: interface_name=[$interface_name]", $prev );
	}

}


