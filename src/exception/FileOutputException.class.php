<?php
/**
* Exception caused by failure in writing file
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_FileOutputException extends Charcoal_RuntimeException
{
	public function __construct( $path, Exception $prev = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $path );

		parent::__construct( "Output to file[$path] failed.", $prev );
	}
}


