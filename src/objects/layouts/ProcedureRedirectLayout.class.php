<?php
/**
* プロシージャをリダイレクトするレイアウト
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ProcedureRedirectLayout extends Charcoal_AbstractLayout
{
	private $_obj_path;
	private $_params;

	/*
	 *	コンストラクタ
	 */
	public function __construct( Charcoal_ObjectPath $obj_path, Charcoal_Properties $params = NULL )
	{
		parent::__construct( p(array()) );

		$this->_obj_path = $obj_path;
		$this->_params    = $params ? $params : m(array());
	}

	/**
	 *	リダイレクト先プロシージャパスを取得
	 */
	public function getProcedurePath()
	{
		return $this->_obj_path;
	}

	/**
	 *	リダイレクト時のパラメータを取得
	 */
	public function getParameters()
	{
		return $this->_params;
	}

	/**
	 *	リダイレクト時のURLを取得
	 */
	public function makeRedirectURL()
	{
		$url = Charcoal_URLUtil::makeAbsoluteURL( $this->_obj_path, $this->_params );

		return $url;
	}

	/*
	 *	文字列化
	 */
	public function toString()
	{
		return "[RedirectWebPage:" . us($this->_obj_path->getObjectPathString()) . "]";
	}
}


