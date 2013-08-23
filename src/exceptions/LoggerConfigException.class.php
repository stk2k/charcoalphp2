<?php
/**
* ロガー設定例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_LoggerConfigException extends Charcoal_ConfigException
{
	public function __construct( Charcoal_String $logger_name, Charcoal_String $config_entry, Charcoal_String $message = NULL )
	{
		$msg = '[logger_name]' . $logger_name->getValue();
		$msg .= '[config_entry]' . $config_entry->getValue();
		if ( $message ){
			$msg .= '[message]' . $message->getValue();
		}

		parent::__construct( s($msg) );
	}
}

return __FILE__;