<?php
/**
* プロシージャを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

interface Charcoal_IProcedure extends Charcoal_ICharcoalObject
{

	/*
	 * 転送先があるか
	 */
	public function hasForwardTarget();

	/*
	 * 転送先を取得
	 */
	public function getForwardTarget();

	/*
	 * プロシージャを実行する
	 */
	public function execute( $request, $response, $session = NULL );
}

