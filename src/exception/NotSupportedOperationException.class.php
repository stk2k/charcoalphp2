<?php
/**
* exception caused by not supported operation being executed
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_NotSupportedOperationException extends Charcoal_RuntimeException
{
	public function __construct( $operation, $prev = NULL )
	{
		parent::__construct( "not supported operation: $operation", $prev );
	}
}


