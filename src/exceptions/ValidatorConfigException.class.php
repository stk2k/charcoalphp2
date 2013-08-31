<?php
/**
* バリデータ設定例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ValidatorConfigException extends Charcoal_ConfigException
{
	public function __construct( Charcoal_String $config_entry, Charcoal_String $message = NULL )
	{
		$msg = '[config_entry]' . $config_entry->getValue();
		if ( $message ){
			$msg .= '[message]' . $message->getValue();
		}

		parent::__construct( s($msg) );
	}
}


