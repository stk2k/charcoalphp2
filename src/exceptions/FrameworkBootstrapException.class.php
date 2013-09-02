<?php
/**
* XML描画例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_FrameworkBootstrapException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $msg, Exception $prev = NULL )
	{
		if ( $prev ) parent::__construct( $msg, $prev ); else parent::__construct( $msg );
	}
}

