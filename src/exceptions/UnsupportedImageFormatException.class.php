<?php
/**
* サポート外画像フォーマット例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_UnsupportedImageFormatException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_Integer $image_type, Exception $previous = NULL )
	{
		$msg = "Unsupported image type: {$image_type}";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}

