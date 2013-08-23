<?php
/**
* クラスローダを定義するインターフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

interface Charcoal_IClassLoader
{
	/*
	 * クラスをロード
	 */
	public function loadClass( Charcoal_String $class_name );

}

return __FILE__;