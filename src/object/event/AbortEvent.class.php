<?php
/**
* イベント処理中断イベントクラス
*
* PHP version 5
*
* @package    objects.events
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_AbortEvent extends Charcoal_SystemEvent 
{
	private $_exit_code;
	private $_abort_type;

	/**
	 *	コンストラクタ
	 */
	public function __construct( Charcoal_Integer $abort_type = NULL, Charcoal_Integer $exit_code = NULL )
	{
		parent::__construct();

		$this->_exit_code = $exit_code ? $exit_code : i(Event::EXIT_CODE_ABORT);
		$this->_abort_type = $abort_type ? $abort_type : i(Event::ABORT_TYPE_AFTER_THIS_LOOP);
	}

	/**
	 *	結果コードを取得
	 */
	public function getExitCode()
	{
		return $this->_exit_code;
	}

	/**
	 *	アボートタイプを取得
	 */
	public function getAbortType()
	{
		return $this->_abort_type;
	}
}

