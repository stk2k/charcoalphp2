<?php
/**
* ファイルアップロード例外：　書き込み不能
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_FileUploadCantWriteException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_UploadedFile $file, Exception $previous = NULL )
	{
		$msg = "File upload failed(UPLOAD_ERR_CANT_WRITE). file=" . print_r($file,true);

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}
return __FILE__;
