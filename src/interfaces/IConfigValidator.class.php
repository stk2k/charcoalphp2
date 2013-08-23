<?php
/**
* 設定バリデーターを定義するインターフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

interface Charcoal_IConfigValidator extends Charcoal_ICharcoalObject
{
	/*
	 *　設定を検証
	 */
	public function validate( Charcoal_Config $config );

}

return __FILE__;