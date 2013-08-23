<?php
/**
* Class path not found exception
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_ClassPathNotFoundException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $class_name, Exception $prev = NULL )
	{
		$msg = "Class path not found for class: $class_name";

		if ( $prev ) parent::__construct( s($msg), $prev ); else parent::__construct( s($msg) );
	}


}
return __FILE__;