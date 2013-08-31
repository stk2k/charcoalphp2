<?php
/**
* ファイルアップロード例外：　一部しかアップロードされなかった
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_FileUploadNoFileException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_UploadedFile $file, Exception $previous = NULL )
	{
		$msg = "File upload failed(UPLOAD_ERR_NO_FILE). file=" . print_r($file,true);

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}

