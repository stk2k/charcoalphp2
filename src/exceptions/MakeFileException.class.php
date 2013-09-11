<?php
/**
* Exception caused by failure in making file 
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_MakeFileException extends Charcoal_RuntimeException
{
	public function __construct( $path, $prev = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $path );

		parent::__construct( "make file failed: $path", $prev );
	}
}


