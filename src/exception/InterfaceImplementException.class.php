<?php
/**
* Exception caused by no implementation is found about specified interface
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_InterfaceImplementException extends Charcoal_RuntimeException
{
	public function __construct( $object, $interface_name, $prev = NULL )
	{
		$object_name = '[' . $object->getClassName() . '] id=' . $object->hash();

		parent::__construct( "Object[$object_name] must implement interface[$interface_name]", $prev );
	}

}


