<?php
/**
* Exception when class loader registration fails
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ClassLoaderRegistrationException extends Charcoal_RuntimeException
{
	public function __construct( $class_loader, $prev = NULL )
	{
		parent::__construct( "failed to register class loader: [$class_loader]", $prev );
	}
}

