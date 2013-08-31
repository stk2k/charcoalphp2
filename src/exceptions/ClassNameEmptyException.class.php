<?php
/**
* クラス名未定義例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_ClassNameEmptyException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $object_name = NULL, Exception $prev = NULL )
	{
		$msg = "Class name is empty. [object_name]$object_name";

		if ( $prev ) parent::__construct( s($msg), $prev ); else parent::__construct( s($msg) );
	}


}
