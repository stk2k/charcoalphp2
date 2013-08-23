<?php
/**
* テーブルモデル項目例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_TableModelFieldException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_ITableModel $model, Charcoal_String $field, Charcoal_String $message, Exception $previous = NULL )
	{
		$msg  = " [table model]" . get_class($model);
		if ( $field ){
			$msg .= " [field]$field";
		}
		if ( $message ){
			$msg .= " [message]$message";
		}

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

	/**
	 *   クラス名
	 */
	static function getClassName()
	{
		return __CLASS__;
	}
}

return __FILE__;