<?php
/**
* NULLポインタ例外
*
* [詳細]
* ・NULLであってはいけない引数にNULLが指定された
* ・想定外のNULLを検出した
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_NullPointerException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $var_name = NULL, Charcoal_String $message = NULL, Exception $previous = NULL )
	{
		$msg = '';
		if ( $var_name ){
			$msg .= "[var name]$var_name";
		}
		if ( $message ){
			$msg .= "[message]$message";
		}

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}

