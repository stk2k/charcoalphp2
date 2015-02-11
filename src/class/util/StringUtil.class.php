<?php
/**
* グラフィック操作に関するユーティリティクラス
*
* PHP version 5
*
* @package    class.util
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_StringUtil
{
	/*
	 * remove control characters from string
	 *
	 * @param string|Charcoal_String $string       Input string include control characters
	 *
	 * preturn string                       control characters removed string
	 */
	public static function removeControlChars( $string )
	{
		$string = us($string);

		return preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $string);
	}

}


