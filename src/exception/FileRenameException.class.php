<?php
/**
* Exception caused by failure in renaming file
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_FileRenameException extends Charcoal_RuntimeException
{
	public function __construct( $old_file, $new_file, $prev = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $old_file );
//		Charcoal_ParamTrait::checkString( 2, $new_file );

		parent::__construct( "Renaming file failed: [{$old_file}] to [{$new_file}]", $prev );
	}
}


