<?php
/**
* Smartyレンダラタスク例外
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_SmartyRendererTaskException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $message, Exception $previous = NULL )
	{
		if ( $previous === NULL ) parent::__construct( $message ); else parent::__construct( $message, $previous );
	}
}

return __FILE__;