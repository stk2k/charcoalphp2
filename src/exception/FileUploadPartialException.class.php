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

class Charcoal_FileUploadPartialException extends Charcoal_RuntimeException
{
	public function __construct( $path, $prev = NULL )
	{
		parent::__construct( "File upload failed(UPLOAD_ERR_PARTIAL): $path", $prev );
	}
}

