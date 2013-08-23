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
 * Name:     kan_utf8_to_euc<br>
 * Purpose:  UTF-8文字エンコーディングをEUC-JPに変換する修飾子
 * @param code
 * @return string
 */
function smarty_modifier_kan_utf8_to_euc( $str )
{
	return mb_convert_encoding($str,"EUC-JP","UTF-8");
}

/* vim: set expandtab: */

?>
