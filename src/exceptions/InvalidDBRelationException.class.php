<?php
/**
* 不正なDBリレーション例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_InvalidDBRelationException extends Charcoal_RuntimeException
{
	public function __construct( ITableModel $model, $field, $relation_type, Charcoal_String $message = NULL, Exception $previous = NULL )
	{
		$msg  = "[model]$model";
		$msg .= "[field]$field";
		$msg .= "[relation_type]$relation_type";
		if ( $message ){
			$msg .= "[message]$message";
		}

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}

return __FILE__;