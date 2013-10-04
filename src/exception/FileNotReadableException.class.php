<?php
/**
* Exception when file is not readable
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_FileNotReadableException extends Charcoal_RuntimeException
{
	public function __construct( $path, Exception $prev = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $path );

		parent::__construct( "File[$path] is not readable.", $prev );
	}
}


