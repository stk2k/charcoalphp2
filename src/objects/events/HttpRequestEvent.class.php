<?php
/**
* HTTPリクエストイベント
*
* PHP version 5
*
* @package    events
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_HttpRequestEvent extends Charcoal_SystemEvent implements Charcoal_IEvent
{
	private $_request;

	/**
	 * コンストラクタ
	 */
	public function __construct( Charcoal_IRequest $request )
	{
		parent::__construct();

		$this->_request = $request;
	}

	/**
	 * パラメータを取得する
	 */
	public function get( Charcoal_String $key )
	{
		return $this->_request->get( $key );
	}

	/**
	 * パラメータ名の一覧を取得する
	 */
	public function getKeys()
	{
		return $this->_request->getKeys();
	}

	/*
	 * 文字列化
	 */
	public function toString()
	{
		$body = '';
		$keys = $this->_request->getKeys();
		foreach( $keys as $key ){
			$value = $this->_request->get( s($key) );
			$body .= " [$key]$value";
		}
		return "[HttpRequestEvent:$body]";
	}

}

