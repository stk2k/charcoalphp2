<?php
/**
* 画像ファイル読み込み例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ImageGetSizeException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_File $file, Exception $previous = NULL )
	{
		$msg = "getimagesize() failed. path=[$file]";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}

