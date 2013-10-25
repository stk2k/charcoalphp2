<?php
/**
* デフォルトのセッションハンドラ実装
*
* PHP version 5
*
* @package    objects.session_handlers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_DefaultSessionHandler extends Charcoal_AbstractSessionHandler
{
	private $save_path;

	/**
	 *	constructor 
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
	public function configure( $config )
	{
		parent::configure( $config );

		$session_name  = $config->getString( 'session_name', '' );
		$save_path     = $config->getString( 'save_path', '', TRUE );
		$lifetime      = $config->getInteger( 'lifetime', 0 );
		$valid_path    = $config->getString( 'valid_path', '' );
		$valid_domain  = $config->getString( 'valid_domain', '' );
		$ssl_only      = $config->getBoolean( 'ssl_only', FALSE );

		$save_path = us($save_path);
		$lifetime  = ui($lifetime);
		$ssl_only  = ub($ssl_only);
		$session_name  = us($session_name);

		// デフォルトのセッション保存先
		if ( !$save_path || !is_dir($save_path) ){
			$save_path = Charcoal_ResourceLocator::getApplicationPath( 'sessions' );
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
		$this->save_path = $save_path;
	}

	/**
	 * セッションファイルパスを取得
	 */
	public function getSessionFile( $id )
	{
		return $this->save_path . "/sess_$id";
	}

	/**
	 * open session
	 */
	public function open( $save_path, $session_name )
	{
		return true;
	}

	/**
	 * close session
	 */
	public function close()
	{
		return true;
	}

	/**
	 * read session data
	 */
	public function read( $id )
	{
		$file = $this->getSessionFile( $id );

		if ( !is_readable($file) ){
			log_warning( "system,session", __CLASS__, "can't read session file[$file]" );
			return NULL;
		}

		$session_date = (string)@file_get_contents( $file );

		log_info( "session", "read", "session_date:$session_date" );

		return  $session_date;
	}

	/**
	 * write session data
	 */
	public function write( $id, $sess_data )
	{
		$file = $this->getSessionFile( $id );

//		log_info( "session", __CLASS__, __CLASS__.'#write: file=' . $file . ' id=' . $id . ' data=' . print_r($sess_data,true) );
		$fp = @fopen($file,'w');
		if ( !$fp ){
			log_warning( "system,debug,error,session", "fopen failed: $file" );
			return false;
		}
		$write = fwrite($fp, $sess_data);
		fclose($fp);

		return $write;
	}

	/**
	 * destroy session
	 */
	public function destroy( $id )
	{
		$file = $this->getSessionFile( $id );

		return @unlink($file);
	}

	/**
	 * garbage collection
	 */
	public function gc( $max_lifetime )
	{
		if ( $dh = opendir($this->save_path) )
		{
			while( ($file = readdir($dh)) !== FALSE )
			{
				$file = $this->save_path . DIRECTORY_SEPARATOR . $file;
				if ( is_file($file) && filemtime($file) + $max_lifetime < time() ){
					@unlink( $file );
				}
			}
			closedir($dh);
		}

		return true;
	}

}

