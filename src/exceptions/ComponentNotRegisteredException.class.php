<?php
/**
* exception caused by failure in finding registered component
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ComponentNotRegisteredException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $component_name, $prev = NULL )
	{
		parent::__construct( "[component name]$component_name", $prev );
	}

}

