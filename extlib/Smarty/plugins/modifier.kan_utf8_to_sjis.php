<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * UTF-8→EUC変換プラグイン
 *
 * Type:     modifier<br>
 * Name:     kan_utf8_to_sjis<br>
 * Purpose:  UTF-8文字エンコーディングをShift_JISに変換する修飾子
 * @param code
 * @return string
 */
function smarty_modifier_kan_utf8_to_sjis( $str )
{
	return mb_convert_encoding($str,"SJIS","UTF-8");
}

/* vim: set expandtab: */

?>
