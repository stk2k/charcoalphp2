<?php
/**
* SQLビルダ設定例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_SQLBuilderConfigException extends Charcoal_ConfigException
{
	public function __construct( Charcoal_String $config_entry, Charcoal_String $message = NULL, Exception $previous = NULL )
	{
		$msg = '[config_entry]' . $config_entry->getValue();
		if ( $message ){
			$msg .= '[message]' . $message->getValue();
		}

		if ( $previous === NULL ) parent::__construct( s($msg) ); else parent::__construct( s($msg), $previous );
	}
}


