<?php
/**
* Exception caused by failure in making directory 
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_MakeDirectoryException extends Charcoal_RuntimeException
{
	public function __construct( $path, Exception $prev = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $path );

		parent::__construct( "mkdir failed: $path", $prev );
	}
}


