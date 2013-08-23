<?php
/**
* SmartGatewayのセッションハンドラ実装
*
* PHP version 5
*
* @package    session_handlers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_SmartGatewaySessionHandler extends Charcoal_CharcoalObject implements Charcoal_ISessionHandler
{
	static $gw;

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
		self::$gw = new Charcoal_SmartGateway();
	}

	/**
	 * コールバック関数：オープン
	 */
	public static function open( $save_path, $session_name )
	{
		return true;
	}

	/**
	 * コールバック関数：クローズ
	 */
	public static function close()
	{
		return true;
	}

	/**
	 * コールバック関数：読み取り
	 */
	public static function read( $id )
	{
		$criteria = new Charcoal_SQLCriteria( s('session_id = ?'), v(array($id)) );

		$session_dto = self::$gw->findFirst( s('session'), $criteria );

		$contents = $session_dto['session_data'];

		return $contents;
	}

	/**
	 * コールバック関数：書き込み
	 */
	public static function write( $id, $sess_data )
	{
		$criteria = new Charcoal_SQLCriteria( s('session_id = ?'), v(array($id)) );

		$dto = self::$gw->findFirst( s('session'), $criteria );

		if ( !$dto ){
			$dto = new SessionDTO();
			$dto->session_id = $id;
		}
		$dto->session_data = $sess_data;

		self::$gw->save( s('session'), $dto );

		return true;
	}

	/**
	 * コールバック関数：破棄
	 */
	public static function destroy( $id )
	{
		self::$gw->destroyById( s('session'), s($id) );

		return true;
	}

	/**
	 * コールバック関数：ガベージコレクション
	 */
	public static function gc( $max_lifetime )
	{
		return true;
	}

}
return __FILE__;
