<?php
/**
* exception caused by parsing annotation of table model
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_TableModelFieldException extends Charcoal_RuntimeException
{
	public function __construct( $model, $field, $message, $prev = NULL )
	{
		parent::__construct( "a field of table model seems to be wrong: [model]$model [field]$field [message]$message", $prev );
	}
}

