<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * 日付書式変換プラグイン
 *
 * Type:     modifier<br>
 * Name:     kan_date_format<br>
 * Purpose:  日付をフォーマットする修飾子
 * @param code
 * @return string
 */
function smarty_modifier_kan_date_format($date_value,$format)
{
	$to = Profile::getGlobalConfig('PHP_CODE');
	$from = Profile::getGlobalConfig('HTML_CODE');

	$format = mb_convert_encoding($format,$to,$from);

	$out = date($format,$date_value);

	$out = mb_convert_encoding($out,$html_encoding,$php_encoding);

	return $out;
}

/* vim: set expandtab: */

?>
