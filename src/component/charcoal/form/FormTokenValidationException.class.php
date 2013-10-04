<?php
/**
* Form token Component Exception
*
* PHP version 5
*
* @package    component.charcoal.form
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_FormTokenValidationException extends Charcoal_RuntimeException
{
	/***
	 *	コンストラクタ
	 **/
	public function __construct( Charcoal_String $messsage, Exception $previous = NULL )
	{
		if ( $previous ) parent::__construct( s($messsage), $previous ); else parent::__construct( s($messsage) );
	}
}


