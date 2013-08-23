<?php
/**
* URLクラス
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_URL extends Charcoal_Object
{
	private $_url;

	/*
	 *	コンストラクタ
	 */
	public function __construct( Charcoal_String $url )
	{
		parent::__construct();

		$this->_url      = $url;
	}

	/*
	 *	URL文字列を取得
	 */
	public function getContent()
	{
		return $this->_url;
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return $this->_url;
	}
}
return __FILE__;
