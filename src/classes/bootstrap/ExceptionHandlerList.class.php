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

class Charcoal_ExceptionHandlerList
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
			$singleton_ = new Charcoal_ExceptionHandlerList();
		}
		return $singleton_;
	}

	/*
	 * 例外ハンドラを追加
	 */
	public static function addExceptionHandler( Charcoal_IExceptionHandler $handler )
	{
		// インスタンスの取得
		$ins = self::getInstance();

		$ins->_list[] = $handler;
	}

	/*
	 * フレームワーク例外ハンドラを実行
	 */
	public static function handleFrameworkException( Charcoal_CharcoalException $e )
	{
		$echo = Charcoal_Framework::testEchoFlag( i(Charcoal_EnumEchoFlag::ECHO_EXCEPTION_HANDLER) );

		if ( $echo ){
			echo "[exception_handler] " . __CLASS__ . "#handleFrameworkException(" . get_class($e) . "): " . eol();
		}

		// インスタンスの取得
		$ins = self::getInstance();

		$ex_name = get_class($e);
		$handlers = Charcoal_System::implodeArray(",",$ins->_list);
		log_info( "system,debug,error", "exception", "Dispatching exception({$ex_name}) to handlers:{$handlers}" );

		// 例外ハンドラを順番に呼び出す
		$list = $ins->_list;

		$result = b(FALSE);
		foreach( $list as $handler ){
			log_info( "system,debug,error", "exception", "calling exception handler[$handler]." );
			$handled = $handler->handleFrameworkException( $e );
			if ( $echo ){
				echo "[exception_handler] " . get_class($handler) . "#handleFrameworkException(): " . $handled . eol();
			}
			log_info( "system,debug,error", "exception", "handled: $handled" );
			$handled = b($handled);
			if ( $handled->isTrue() ){
				$result = b(TRUE);
				break;
			}
		}
		if ( $echo ){
			echo "[exception_handler] result: " . $result . eol();
		}

		return $result;
	}

	/*
	 * 例外ハンドラを実行
	 */
	public static function handleException( Exception $e )
	{
		$echo = Charcoal_Framework::testEchoFlag( i(Charcoal_EnumEchoFlag::ECHO_EXCEPTION_HANDLER) );

		if ( $echo ){
			echo "[exception_handler] " . __CLASS__ . "#handleException(" . get_class($e) . "): " . eol();
		}

		// インスタンスの取得
		$ins = self::getInstance();

		$ex_name = get_class($e);
		$handlers = Charcoal_System::implodeArray(",",$ins->_list);
		log_info( "system,debug,error", "exception", "Dispatching exception({$ex_name}) to handlers:{$handlers}" );

		// 例外ハンドラを順番に呼び出す
		$list = $ins->_list;

		$result = b(FALSE);
		foreach( $list as $handler ){
			log_info( "system,debug,error", "exception", "calling exception handler[$handler]." );
			$handled = $handler->handleException( $e );
			if ( $echo ){
				echo "[exception_handler] " . get_class($handler) . "#handleException(): " . $handled . eol();
			}
			log_info( "system,debug,error", "exception", "handled: $handled" );
			$handled = b($handled);
			if ( $handled->isTrue() ){
				$result = b(TRUE);
				break;
			}
		}
		if ( $echo ){
			echo "[exception_handler] result: " . $result . eol();
		}

		return $result;
	}

	/**
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return implode( ",", $this->_list );
	}
}
return __FILE__;