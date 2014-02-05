<?php
/**
* exception caused by configuration of component
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ComponentConfigException extends Charcoal_ConfigException
{
	public function __construct( $component_name, $entry, $message = NULL, $prev = NULL )
	{
		Charcoal_ParamTrait::checkString( 1, $component_name );
		Charcoal_ParamTrait::checkString( 2, $entry );
		Charcoal_ParamTrait::checkString( 3, $message );
		Charcoal_ParamTrait::checkException( 4, $prev, TRUE );

		parent::__construct( "component($component_name) config maybe wrong: [entry]$entry [message]$message", $prev );
	}
}

