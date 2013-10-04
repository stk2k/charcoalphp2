<?php
/**
* Exception caused by failure in manipulating uploaded file
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_FileUploadExtensionException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_UploadedFile $file, $prev = NULL )
	{
		parent::__construct( "File upload failed(UPLOAD_ERR_EXTENSION): $path", $prev );
	}

}

