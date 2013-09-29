<?php
/**
* Form token Component Exception
*
* PHP version 5
*
* @package    components.charcoal.form
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
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


