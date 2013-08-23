<?php
/**
* 変換例外
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_TransformerException extends Charcoal_RuntimeException
{
	public function __construct( IModel $in_model, IModel $out_model, Charcoal_String $message = NULL, Exception $previous = NULL )
	{
		$msg  = " [IN model]" . get_class($in_model);
		$msg .= " [OUT model]" . get_class($out_model);
		if ( $message ){
			$msg .= " [message]$message";
		}

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}
}

return __FILE__;