<?php
/**
* コアフック定数
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_EnumCoreHookStage
{
//	const SYSTEM_LOG_INIT                 = 0;
	const START_OF_BOOTSTRAP              = 1;
	const BEFORE_INIT_FRAMEWORK           = 2;
	const AFTER_INIT_FRAMEWORK            = 3;
	const BEFORE_REG_CLASS_LOADERS        = 4;
	const CREATE_FRAMEWORK_CLASS_LOADER   = 5;
	const REG_FRAMEWORK_CLASS_LOADER      = 6;
	const CREATE_CLASS_LOADER             = 7;
	const REG_CLASS_LOADER                = 8;
	const AFTER_REG_CLASS_LOADERS         = 9;
	const BEFORE_REG_EXCEPTION_HANDLERS   = 10;
	const CREATE_EXCEPTION_HANDLER        = 11;
	const AFTER_REG_EXCEPTION_HANDLERS    = 12;
	const BEFORE_REG_USER_LOGGERS         = 13;
	const CREATE_USER_LOGGER              = 14;
	const AFTER_REG_USER_LOGGERS          = 15;
	const BEFORE_REG_EXTLIB_DIR           = 16;
	const ADD_EXTLIB_DIR                  = 17;
	const AFTER_REG_EXTLIB_DIR            = 18;
	const BEFORE_SET_SESSION_HANDLER      = 19;
	const AFTER_SET_SESSION_HANDLER       = 20;
	const BEFORE_START_SESSION            = 21;
	const AFTER_START_SESSION             = 22;
	const BEFORE_ROUTING_RULE             = 23;
	const AFTER_ROUTING_RULE              = 24;
	const BEFORE_ROUTER                   = 25;
	const AFTER_ROUTER                    = 26;
	const BEFORE_CREATE_PROCEDURE         = 27;
	const AFTER_CREATE_PROCEDURE          = 28;
	const BEFORE_PROCEDURE_FORWARD        = 29;
	const PRE_PROCEDURE_FORWARD           = 30;
	const POST_PROCEDURE_FORWARD          = 31;
	const AFTER_PROCEDURE_FORWARD         = 32;
	const BEFORE_CREATE_CONTAINER         = 33;
	const AFTER_CREATE_CONTAINER          = 34;
	const BEFORE_REG_RESPONSE_FILTERS     = 35;
	const CREATE_RESPONSE_FILTER          = 36;
	const AFTER_REG_RESPONSE_FILTERS      = 37;
	const END_OF_BOOTSTRAP                = 38;
	const BEFORE_EXECUTE_PROCEDURES       = 39;
	const PRE_EXECUTE_PROCEDURE           = 40;
	const POST_EXECUTE_PROCEDURE          = 41;
	const AFTER_EXECUTE_PROCEDURES        = 42;
	const START_OF_SHUTDOWN               = 43;
	const BEFORE_SAVE_SESSION             = 44;
	const AFTER_SAVE_SESSION              = 45;
	const BEFORE_DESTROY_CONTAINER        = 46;
	const AFTER_DESTROY_CONTAINER         = 47;
	const BEFORE_TERMINATE_LOGGERS        = 48;
	const AFTER_TERMINATE_LOGGERS         = 49;
	const END_OF_SHUTDOWN                 = 50;
}
return __FILE__;
