<?php
/**
* レイアウト情報を保持するクラス
*
* PHP version 5
*
* @package    class.core
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_Layout extends Charcoal_Object
{
	private $attributes;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $attributes )
	{
//		Charcoal_ParamTrait::validateProperties( 1, $attributes );

		parent::__construct();

		$this->attributes = p($attributes);
	}

	/**
	 *	レイアウト属性を取得
	 */
	public function getAttribute( $key )
	{
		return $this->attributes->get( $key );
	}

	/*
	 *	文字列化
	 */
	public function toString()
	{
		return "[Layout:{$this->attributes}]";
	}
}


