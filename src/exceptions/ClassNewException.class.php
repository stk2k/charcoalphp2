<?php
/**
* クラス作成例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_ClassNewException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_Class $klass, Charcoal_Vector $args, Exception $previous = NULL )
	{
		$class_name = $klass->getClassName();

		$msg = "[class_name]$class_name [args]$args";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}


}
