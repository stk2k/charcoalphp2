<?php
/**
* PHPソース要素クラス
*
* PHP version 5
*
* @package    class.debug
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_PhpSourceElement extends Charcoal_Object
{
	const TYPE_KEYWORD       = 'K';		// PHPキーワード
	const TYPE_IDENTIFIER    = 'I';		// 識別子
	const TYPE_COMMENT       = 'C';		// コメント
	const TYPE_DELIMITER     = 'D';		// 区切り記号
	const TYPE_CONST_STRING  = 'S';		// 文字列定数

	/**
	 *    ソースコード
	 */
	private $_code;

	/**
	 *    タイプ
	 */
	private $_type;

	/**
	 *    ID
	 */
	private $_id;

	/**
	 *    パース状態
	 */
	private $_state;

	/**
	 *    ID生成カウンタ
	 */
	static $id_cnt = 100;

	/**
	 *	コンストラクタ
	 */
	public function __construct( $code, $type, $state )
	{
		$this->_id = ++ self::$id_cnt;

		$this->_code = $code;
		$this->_type = $type;
		$this->_state = $state;
	}

	/**
	 *	IDを取得
	 */
	public function getID()
	{
		return $this->_id;
	}

	/**
	 *	コードを取得
	 */
	public function getCode()
	{
		return $this->_code;
	}

	/**
	 *	タイプを取得
	 */
	public function getType()
	{
		return $this->_type;
	}

	/**
	 *	パース状態を取得
	 */
	public function getState()
	{
		return $this->_state;
	}

	/**
	 *	文字列化
	 */
	public function toString()
	{
		$code  = htmlspecialchars($this->_code);
		$type  = $this->_type;
		$id    = $this->_id;
		$state = $this->_state;
		return "[$code@$type:$id:$state]";
	}
}

