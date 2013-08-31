<?php
/**
* constant class of framework run mode
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_EnumEchoFlag
{
	const ECHO_ALL                 = 0xFFFFFFFF;
	const ECHO_EXCEPTION           = 0x00000001;
	const ECHO_PROFILE             = 0x00000002;
	const ECHO_LOGGER              = 0x00000004;
	const ECHO_CLASS_LOADER        = 0x00000008;
	const ECHO_CONFIG              = 0x00000010;
	const ECHO_FACTORY             = 0x00000020;
	const ECHO_CONTAINER           = 0x00000040;
	const ECHO_SHUTDOWN_HOOK       = 0x00000080;
	const ECHO_EXCEPTION_HANDLER   = 0x00000100;
	const ECHO_DEBUGTRACE_RENDERER = 0x00000200;
}

