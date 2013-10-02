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
//		Charcoal_ParamTrait::checkString( 1, $file );
//		Charcoal_ParamTrait::checkInteger( 2, $line );
//		Charcoal_ParamTrait::checkInteger( 3, $parameter_id );
//		Charcoal_ParamTrait::checkString( 4, $parameter_type );
//		Charcoal_ParamTrait::checkObject( 5, $actual );

		$parameter_type = is_array($parameter_type) ? implode("/",$parameter_type) : $parameter_type;
		$actual = Charcoal_System::toString( $actual );
		$actual_type = gettype( $actual );
		$message = "parameter '$parameter_id' must be instanceof '$parameter_type','$actual'($actual_type) is passed at $file($line).";

		parent::__construct( $message );
	}

}
