<?php
/**
* クラスローダを定義するインターフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

interface Charcoal_IClassLoader
{
	/*
	 * クラスをロード
	 */
	public function loadClass( $class_name );

}

