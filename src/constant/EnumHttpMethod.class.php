<?php
/**
* HTTPメソッド定数
*
* PHP version 5
*
* @package    constant
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_EnumHttpMethod extends Charcoal_Enum
{
	const HTTP_GET          = 1;	
	const HTTP_POST         = 2;
	const HTTP_HEAD         = 3;
	const HTTP_PUT          = 4;
	const HTTP_OPTIONS      = 5;
	const HTTP_DELETE       = 6;
	const HTTP_TRACE        = 7;
	const HTTP_PATCH        = 8;
	const HTTP_LINK         = 9;
	const HTTP_UNLINK       = 10;
}

