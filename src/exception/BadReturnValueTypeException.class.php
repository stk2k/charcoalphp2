<?php
/**
* exception caused by not suitable for exit code
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_BadExitCodeException extends Charcoal_RuntimeException
{
	public function __construct( $exit_code, $prev = NULL )
	{
		$exit_code = Charcoal_System::toString( $exit_code );
		$type = gettype($exit_code);

		parent::__construct( "Bad exit code: [$exit_code]($type)", $prev );
	}
}


