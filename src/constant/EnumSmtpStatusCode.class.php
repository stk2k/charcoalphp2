<?php
/**
* SMTPステータスコード定数
*
* PHP version 5
*
* @package    constant
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_EnumSmtpStatusCode extends Charcoal_Enum
{
	//=================================
	// 1xx : Positive Preliminary reply
	//=================================

	//=================================
	// 2xx : Positive Completion reply
	//=================================

	//=================================
	// 5xx : Permanent Negative Completion reply
	//=================================
	const BAD_SEQUENCE_OF_COMMANDS          = 503;	// Bad sequence of commands
	const SYNTAX_ERROR                      = 555;	// syntax error
}

