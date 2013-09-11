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
	public function __construct( $file, $line, $parameter_id, $parameter_type, $actual )
	{
		$parameter_type = Charcoal_System::toString( $parameter_type );
		$actual = Charcoal_System::toString( $actual );
		$actual_type = Charcoal_System::getType( $actual );
		$message = "parameter '$parameter_id' must be instanceof '$parameter_type','$actual'($actual_type) is passed at $file($line).";

		parent::__construct( $message );
	}

}

