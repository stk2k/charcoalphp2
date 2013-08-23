<?php
/**
* 必須アノテーション例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_AnnotaionMandatoryException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $model_name, Charcoal_String $field_name, Charcoal_String $annotaion_name, Exception $previous = NULL )
	{
		$msg  = "annotation following is mandatory: [model name]$model_name";
		$msg .= "[field name] $field_name";
		$msg .= "[annotaion name] $annotaion_name";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}
return __FILE__;
