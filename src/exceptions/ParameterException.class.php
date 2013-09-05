<?php
/**
* exception when a parameter is wrong
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ParameterException extends Charcoal_LogicException
{
	public function __construct( $parameter_id, $parameter_type, $actual )
	{
		$actual_type = is_object($actual) ? get_class($actual) : gettype($actual);
		$message = "parameter[$parameter_id] must be instanceof [$parameter_type],[$actual_type] is passed.";

		parent::__construct( $message );
	}

}

