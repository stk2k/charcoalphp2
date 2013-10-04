<?php
/**
* exception caused by not suitable for number object
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_NonNumberException extends Charcoal_RuntimeException
{
	public function __construct( $value, $prev = NULL )
	{
		parent::__construct( "can't convert to number object: $value", $prev );
	}
}


