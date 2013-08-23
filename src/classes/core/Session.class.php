<?php
/**
* セッションをラップするクラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Session extends Charcoal_Object
{
	var $_data;

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		$this->_data = array();

		$req_path = Charcoal_Framework::getRequestPath();
//		log_info( "debug, session", "session", "request_path=" . $req_path );
	}

	/*
	 *	セッション名を取得
	 */
	public function getName()
	{
		return session_name();
	}

	/*
	 *	セッション保存場所を取得
	 */
	public function getSavePath()
	{
		return session_save_path();
	}

	/*
	 *	セッションクッキーのパラメータを取得
	 */
	public function getCookieParameter( Charcoal_String $key )
	{
		$a = session_get_cookie_params();
		$key = us( $key );
		return $a[ $key ];
	}

	/*
	 *	セッションクッキーのパラメータをすべて取得
	 */
	public function getCookieParameters()
	{
		return session_get_cookie_params();
	}

	/*
	 *	初期化
	 */
	public function clear()
	{
		$this->_data = array();

//		log_info( "debug, session", "session", "clear()" );
	}

	/*
	 *	ID初期化
	 */
	public function regenerateID()
	{
		$old_id = session_id();

		$result = session_regenerate_id( TRUE );

		$new_id = session_id();

//		log_info( "debug, session", "session", "regenerateID() old=$old_id new=$new_id result=" . ($result ? "TRUE" : "FALSE") );
	}

	/*
	 *    キー一覧を取得
	 */
	public function getKeys()
	{
		return array_keys( $this->_data );
	}

	/*
	 *    パラメータを取得
	 */
	public function get( Charcoal_String $key )
	{
		$key = us( $key );
		$value = isset($this->_data[ $key ]) ? $this->_data[ $key ] : NULL;

//		log_debug( "debug, session", "session", "get($key)=$value" );

		return $value;
	}

	/*
	 *    パラメータを設定
	 */
	public function set( Charcoal_String $key, $value )
	{
		$key = us( $key );
		$this->_data[ $key ] = $value;

//		log_debug( "debug, session", "session", "set($key," . print_r($value,true) . ")" );
	}

	/*
	 *    パラメータの設定を解除
	 */
	public function remove( Charcoal_String $key )
	{
		$key = us( $key );
		$value = isset($this->_data[ $key ]) ? $this->_data[ $key ] : NULL;

		if ( $value ){
			unset( $this->_data[ $key ] );
		}

//		log_debug( "debug, session", "session", "remove($key)=$value" );

		return $value;
	}

	/**
	 * セッションを開始する
	 */
	public function start()
	{
		session_cache_limiter('private, must-revalidate');
//		session_cache_limiter('private');
//		session_cache_limiter('private_no_expire');
//		session_cache_limiter('nocache');
		session_start();

//		log_info( "debug, session", "session", "start() session_id=" . session_id() . " IP=" . $_SERVER["REMOTE_ADDR"] );
	}

	/**
	 * セッションを終了する
	 */
	public function close()
	{
		session_write_close();

//		log_info( "debug, session", "session", "close()" );
	}

	/*
	 *    セッションを破棄
	 */
	public function destroy()
	{
		$this->clear();
		session_unset();
		session_destroy();

//		log_info( "debug, session", "session", "destroy()" );
	}

	/**
	 * セッションを復元する
	 */
	public function restore()
	{
//		log_info( "debug, session", "session", "restore() start" );
//		log_info( "debug, session", "session", "_SESSION:" . print_r($_SESSION,true) );
//		log_info( "debug, session", "session", "this->_data:" . print_r($this->_data,true) );

		// 配列の初期化
		$this->clear();

		log_info( "debug, session", "session", "_SESSION:" . print_r($_SESSION,true) );

		// 各配列の値をデシリアライズ
		$keys = array_keys( $_SESSION );
		if ( $keys ){
			foreach( $keys as $key ){
				$value = unserialize( $_SESSION[$key] );
				$this->set( s($key), $value );
				log_info( "debug, session", "session", "[$key]=" . print_r($value,true) );
			}
		}


		log_info( "debug, session", "session", "restored :" . print_r($this->_data,true) );
//		log_info( "debug, session", "session", "restore() end" );
	}

	/**
	 * セッションを保存する
	 */
	public function save()
	{
//		log_info( "debug, session", "session", 'save() start' );

		// 初期化しておく
		$_SESSION = array();

		$keys = $this->getKeys();
//		log_info( "debug, session", "session", "keys:" . print_r($keys,true) );

		// メモリ上に設定されたキーの値をシリアライズ
		foreach( $keys as $key ){
			$value = $this->get( s($key) );
			$_SESSION[ $key ] = serialize($value);
//			log_info( "debug, session", "session", "[$key]=" . print_r($value,true) );
		}

//		log_info( "debug, session", "session", 'save() end' );
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return Charcoal_System::arrayToString( $this->_data );
	}
}
return __FILE__;