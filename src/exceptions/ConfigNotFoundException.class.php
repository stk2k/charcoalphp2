<?php
/**
* exception when config file not found 
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ConfigNotFoundException extends Charcoal_RuntimeException
{
	public function __construct( Charcoal_ObjectPath $object_path, Charcoal_String $type_name, Charcoal_Vector $sources, Exception $prev = NULL )
	{
		$msg  = "Config loading was failed!: object_path=" . $object_path . " type_name=[$type_name]." . eol();
		$msg .= "Please check if at least one of files below exists." . eol();

		foreach( $sources as $src ){
			$msg .= "[$src]" . eol();
		}

		if ( $prev ) parent::__construct( s($msg), $prev ); else parent::__construct( s($msg) );
	}
}

return __FILE__;