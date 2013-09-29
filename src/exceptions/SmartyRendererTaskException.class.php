<?php
/**
* exception caused by failure in rendering by smarty
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_SmartyRendererTaskException extends Charcoal_RuntimeException
{
	public function __construct( $message, $prev = NULL )
	{
		parent::__construct( $message, $prev );
	}
}

