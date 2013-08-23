<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * 性別コード変換プラグイン
 *
 * Type:     modifier<br>
 * Name:     kan_sex<br>
 * Purpose:  性別コードから文字列（１文字）に変換する修飾子
 * @param code
 * @return string
 */
function smarty_modifier_kan_sex( $code )
{
	switch ( $code ){
	case '0':
		return "男性";
	case '1':
		return "女性";
	}

	return "?";
}

/* vim: set expandtab: */

?>
