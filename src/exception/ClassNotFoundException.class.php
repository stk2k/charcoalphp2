<?php
/**
* exception causes by failure in finding class definition
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_ClassNotFoundException extends Charcoal_RuntimeException
{
	public function __construct( $class_name, $prev = NULL )
	{
		parent::__construct( "Class not found: class_name=[$class_name]", $prev );
	}


}
