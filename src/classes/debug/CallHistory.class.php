<?php
/**
* 呼び出し履歴クラス
*
* PHP version 5
*
* @package    classes.debug
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_CallHistory extends Charcoal_Object
{
	private $_args;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $args )
	{
		parent::__construct();

		$this->_args = $args;
	}

	/*
	 *	引数の数を取得
	 */
	public function getArgCount()
	{
		return count($this->_args);
	}

	/*
	 *	n番目のの引数を取得
	 */
	public function getArg( $no )
	{
		return $this->_args[ $no ];
	}


	/*
	 *	文字列化
	 */
	public function toString()
	{
		return Charcoal_System::implodeArray( ',', $this->_args );
	}
}

