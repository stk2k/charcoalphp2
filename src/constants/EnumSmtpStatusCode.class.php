<?php
/**
* SMTPステータスコード定数
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_EnumSmtpStatusCode extends Charcoal_Object
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

