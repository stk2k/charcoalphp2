<?php
/**
* Exception when sandbox is not loaded yet
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_SandboxNotLoadedException extends Charcoal_RuntimeException
{
	public function __construct( $file, $line, $prev = NULL )
	{
		parent::__construct( "Sandbox is not ready at $file($line)", $prev );
	}
}

