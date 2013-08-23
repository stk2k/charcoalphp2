<?php
/**
* 定数クラス：　PDFセル境界線
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class EnumPDFCellBorder
{
	const ALL               = 0xffff;		// LEFT | TOP | RIGHT | BOTTOM
	const NOTHING           = 0x0000;		// 
	const LEFT              = 0x0001;		// LEFT
	const TOP               = 0x0002;		// TOP
	const RIGHT             = 0x0004;		// RIGHT
	const BOTTOM            = 0x0008;		// BOTTOM

}

return __FILE__;