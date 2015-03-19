<?php
/**
* URLにリダイレクトするレイアウト
*
* PHP version 5
*
* @package    objects.layouts
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_URLRedirectLayout extends Charcoal_AbstractLayout
{
	private $url;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $url )
	{
		Charcoal_ParamTrait::validateString( 1, $url );

		parent::__construct( p(array()) );

		$this->url = $url;
	}

	/**
	 *	リダイレクト先URLを取得
	 */
	public function getURL()
	{
		return $this->url;
	}

	/**
	 *	リダイレクト時のURLを取得
	 */
	public function makeRedirectURL()
	{
		return $this->url;
	}

	/*
	 *	文字列化
	 */
	public function toString()
	{
		return "[RedirectWebPage:" . us($this->_obj_path->getPathString()) . "]";
	}
}


