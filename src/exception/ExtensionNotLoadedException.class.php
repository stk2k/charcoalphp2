<?php
/**
* exception caused when specified extension is not found
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ExtensionNotLoadedException extends Charcoal_RuntimeException
{
	public function __construct( $extension, $prev = NULL )
	{
		$msg = 'extension[' . $extension . '] is not loaded!';
		log_error( 'system,debug,error', $msg, 'extension' );

		parent::__construct( $msg, $prev );
	}
}

