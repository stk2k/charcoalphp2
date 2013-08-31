<?php
/**
* オブジェクト設定例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ObjectConfigException extends Charcoal_ConfigException
{
	public function __construct( Charcoal_CharcoalObject $object, Charcoal_String $config_entry, Charcoal_String $message = NULL, Exception $prev = NULL )
	{
		$msg  = '[obj_path]' . $obj_path->getObjectPath();
		$msg .= ' [config_entry]' . $config_entry->getValue();
		if ( $message ){
			$msg .= ' [message]' . $message->getValue();
		}

		if ( $prev ) parent::__construct( s($msg), $prev ); else parent::__construct( s($msg) );
	}
}


