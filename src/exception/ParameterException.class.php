<?php
/**
* exception when a parameter is wrong
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ParameterException extends Charcoal_LogicException
{
	public function __construct( $file, $line, $parameter_id, $parameter_type, $actual )
	{
//		Charcoal_ParamTrait::checkString( 1, $file );
//		Charcoal_ParamTrait::checkInteger( 2, $line );
//		Charcoal_ParamTrait::checkInteger( 3, $parameter_id );
//		Charcoal_ParamTrait::checkString( 4, $parameter_type );

		$parameter_type = is_array($parameter_type) ? implode("/",$parameter_type) : $parameter_type;
		$actual = Charcoal_System::toString( $actual );
		$actual_type = Charcoal_System::getType( $actual );
		$message = "parameter '$parameter_id' must be instanceof '$parameter_type', but $actual($actual_type) is passed at $file($line).";

		parent::__construct( $message );
	}

}

