<?php
/**
* exception caused by failure in finding registered component
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ComponentNotRegisteredException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $component_name, $prev = NULL )
	{
		parent::__construct( "[component name]$component_name", $prev );
	}

}

