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
	private $_url;

	/*
	 *	コンストラクタ
	 */
	public function __construct( Charcoal_String $url )
	{
		parent::__construct( p(array()) );

		$this->_url = $url;
	}

	/**
	 *	リダイレクト先URLを取得
	 */
	public function getURL()
	{
		return $this->_url;
	}

	/**
	 *	リダイレクト時のURLを取得
	 */
	public function makeRedirectURL()
	{
		return $this->_url;
	}

	/*
	 *	文字列化
	 */
	public function toString()
	{
		return "[RedirectWebPage:" . us($this->_obj_path->getPathString()) . "]";
	}
}


