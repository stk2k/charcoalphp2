<?php
/**
* デフォルトのセッションハンドラ実装
*
* PHP version 5
*
* @package    session_handlers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_DefaultSessionHandler extends Charcoal_CharcoalObject implements Charcoal_ISessionHandler
{
	static $save_path;

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
		$session_name  = $config->getString( s('session_name'), s('') );
		$save_path     = $config->getString( s('save_path'), s('') );
		$lifetime      = $config->getInteger( s('lifetime'), i(0) );
		$valid_path    = $config->getString( s('valid_path'), s('') );
		$valid_domain  = $config->getString( s('valid_domain'), s('') );
		$ssl_only      = $config->getBoolean( s('ssl_only'), b(FALSE) );

		$save_path = us($save_path);
		$lifetime  = ui($lifetime);
		$ssl_only  = ub($ssl_only);
		$session_name  = us($session_name);

		// デフォルトのセッション保存先
		if ( !$save_path || !is_dir($save_path) ){
			$save_path = Charcoal_ResourceLocator::getApplicationPath( s('sessions') );
		}

		// セッション初期化処理
//		session_set_cookie_params( $lifetime, "$valid_path", "$valid_domain", $ssl_only );
		session_save_path( $save_path );
//		$session_name = session_name( $session_name ? $session_name : APPLICATION );
		session_name("PHPSESSID");
		//session_regenerate_id( TRUE );

//		log_info( "session", __CLASS__, "session_name:$session_name" );
//		log_info( "session", __CLASS__, "save_path:$save_path" );
//		log_info( "session", __CLASS__, "lifetime:$lifetime" );
//		log_info( "session", __CLASS__, "valid_path:$valid_path" );
//		log_info( "session", __CLASS__, "valid_domain:$valid_domain" );
//		log_info( "session", __CLASS__, "ssl_only:$ssl_only" );

		// メンバーに保存
		self::$save_path = $save_path;
	}

	/**
	 * セッションファイルパスを取得
	 */
	public static function getSessionFile( $id )
	{
		return self::$save_path . "/sess_$id";
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
		$file = self::getSessionFile( $id );

		if ( !is_readable($file) ){
			log_warning( "system,session", __CLASS__, "can't read session file[$file]" );
		}

		$session_date = (string)@file_get_contents( $file );

		log_info( "session", "read", "session_date:$session_date" );

		return  $session_date;
	}

	/**
	 * コールバック関数：書き込み
	 */
	public static function write( $id, $sess_data )
	{
		$file = self::getSessionFile( $id );

//		log_info( "session", __CLASS__, __CLASS__.'#write: file=' . $file . ' id=' . $id . ' data=' . print_r($sess_data,true) );
		$fp = @fopen($file,'w');
		if ( !$fp ){
			return false;
		}
		$write = fwrite($fp, $sess_data);
		fclose($fp);

		return $write;
	}

	/**
	 * コールバック関数：破棄
	 */
	public static function destroy( $id )
	{
		$file = self::getSessionFile( $id );

		return @unlink($file);
	}

	/**
	 * コールバック関数：ガベージコレクション
	 */
	public static function gc( $max_lifetime )
	{
		if ( $dh = opendir(self::$save_path) )
		{
			while( ($file = readdir($dh)) !== FALSE )
			{
				$file = self::$save_path . DIRECTORY_SEPARATOR . $file;
				if ( filemtime($file) + $max_lifetime < time() ){
					@unlink( $file );
				}
			}
			closedir($dh);
		}

		return true;
	}

}

