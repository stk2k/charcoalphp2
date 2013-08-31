<?php
/**
* クラス未定義例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_ClassNotFoundException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $class_name, Exception $prev = NULL )
	{
		$msg = "Class not found: class_name=[$class_name]";

		if ( $prev ) parent::__construct( s($msg), $prev ); else parent::__construct( s($msg) );
	}


}
