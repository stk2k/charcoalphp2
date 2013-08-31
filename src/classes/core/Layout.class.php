<?php
/**
* レイアウト情報を保持するクラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Layout extends Charcoal_Object
{
	private $_layout_attributes;

	/*
	 *	コンストラクタ
	 */
	public function __construct( Charcoal_Properties $layout_attributes )
	{
		parent::__construct();

		$this->_layout_attributes = $layout_attributes;
	}

	/**
	 *	レイアウト属性を取得
	 */
	public function getAttribute( Charcoal_String $key )
	{
		return $this->_layout_attributes->get( $key );
	}

	/*
	 *	文字列化
	 */
	public function toString()
	{
		return "[Layout:" . $this->_layout_attributes->toString() . "]";
	}
}


