<?php
/**
* レイアウト例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_LayoutNotFoundException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_ILayoutManager $lm, Charcoal_String $layout_name, Exception $previous = NULL )
	{
		$layout_manager = $lm->getObjectPath();

		$msg  = "[layout manager] $layout_manager [layout name] $layout_name";

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}
}

return __FILE__;