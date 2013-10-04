<?php
/**
* exception caused by not suitable for boolean object
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_NonBooleanException extends Charcoal_RuntimeException
{
	public function __construct( $value, $prev = NULL )
	{
		parent::__construct( "can't convert to boolean object: $value", $prev );
	}
}


