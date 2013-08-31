<?php
/**
* Temporary Directory Component Exception
*
* PHP version 5
*
* @package    components.charcoal.file
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_TempDirComponentException extends Charcoal_RuntimeException
{
	/***
	 *	コンストラクタ
	 **/
	public function __construct( Charcoal_String $messsage, Exception $previous = NULL )
	{
		if ( $previous ) parent::__construct( s($messsage), $previous ); else parent::__construct( s($messsage) );
	}
}


