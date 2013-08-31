<?php
/**
* 例外ハンドラリスト
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_DebugTraceRendererList
{
	var $_list;

	/*
	 *    コンストラクタ
	 */
	private function __construct()
	{
		$this->_list = array();
	}

	/*
	 *    唯一のインスタンス取得
	 */
	public static function getInstance()
	{
		static $singleton_;
		if ( $singleton_ == null ){
			$singleton_ = new Charcoal_DebugTraceRendererList();
		}
		return $singleton_;
	}

	/*
	 * 例外ハンドラを追加
	 */
	public static function addDebugtraceRenderer( Charcoal_IDebugtraceRenderer $renderer )
	{
		// インスタンスの取得
		$ins = self::getInstance();

		$ins->_list[] = $renderer;
	}

	/**
	 * Render debug trace
	 *
	 * @param Charcoal_String $title  title
	 */
	public static function render( Exception $e )
	{
		// インスタンスの取得
		$ins = self::getInstance();

		// デバッグトレースレンダラを順番に呼び出す
		$list = $ins->_list;

		$result = b(FALSE);
		foreach( $list as $renderer ){
			$ret = $renderer->render( $e );
			if ( $ret && $ret instanceof Charcoal_Boolean && $ret->isTrue() ){
				$result = b(TRUE);
			}
		}

		return $result;
	}

}
