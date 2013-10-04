<?php
/**
* Temporary File Component Exception
*
* PHP version 5
*
* @package    component.charcoal.file
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_TempFileComponentException extends Charcoal_RuntimeException
{
	/***
	 *	コンストラクタ
	 **/
	public function __construct( Charcoal_String $messsage, Exception $previous = NULL )
	{
		if ( $previous ) parent::__construct( s($messsage), $previous ); else parent::__construct( s($messsage) );
	}
}


