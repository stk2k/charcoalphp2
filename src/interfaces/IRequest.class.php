<?php
/**
* リクエストを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_IRequest extends Charcoal_ICharcoalObject, Charcoal_IProperties
{
	/*
	 *    プロシージャパスを取得
	 */
	public function getProcedurePath();

	/*
	 *    リクエストIDを取得
	 */
	public function getRequestID();

}

