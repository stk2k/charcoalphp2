<?php
/**
* コンポーネントが未登録例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ComponentNotRegisteredException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_String $component_name, Exception $previous = NULL )
	{
		$msg = "[component name]" . $component_name;

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}

}

return __FILE__;