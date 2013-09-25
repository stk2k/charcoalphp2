<?php
/**
* request class for http
*
* PHP version 5
*
* @package    requests
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_HttpRequest extends Charcoal_AbstractRequest
{
	private $_proc_path;
	private $_id;
	private $_cookie;
	private $_proc_key;

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();

		// magic_quotes_gpc対策
		if ( get_magic_quotes_gpc() == 1 ){
			$get = array_map( array(CHARCOAL_CLASS_PREFIX . 'System','stripSlashes'), $_GET); 
			$post = array_map( array(CHARCOAL_CLASS_PREFIX . 'System','stripSlashes'), $_POST); 
		}
		else{
			$get = $_GET;
			$post = $_POST;
		}

		$this->values = array_merge( $get, $post );

		// リクエストID
		$this->_id = Charcoal_System::hash();

	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		// use cookie
		$use_cookie  = $this->getSandbox()->getProfile()->getString( 'USE_COOKIE', FALSE );
		$this->_cookie = $use_cookie ? new Charcoal_CookieReader() : NULL;

		// プロシージャキー
		$this->_proc_key  = $this->getSandbox()->getProfile()->getString( 'PROC_KEY', 'proc' );

	}

	/*
	 *  Get all cookie values as array
	 *
	 * @return array
	 */
	public function getCookies()
	{
		return $this->_cookie->toArray();
	}

	/*
	 *  Get cookie value
	 *
	 * @param Charcoal_String $name   cookie name to get
	 *
	 * @return Charcoal_String
	 */
	public function getCookie( Charcoal_String $name )
	{
		return $this->_cookie->getValue( $name );
	}

	/*
	 *  Set cookie value
	 *
	 * @param Charcoal_String $name   cookie name to set
	 * @param Charcoal_String $value   cookie value to set
	 *
	 * @return Charcoal_String
	 */
	public function setCookie( Charcoal_String $name, Charcoal_String $value )
	{
		$this->_cookie->setValue( $name, $value );
	}

	/*
	 *    プロシージャパスを取得
	 */
	public function getProcedurePath()
	{
		return $this->getString( $this->_proc_key );
	}

	/*
	 * リクエストIDを取得
	 */
	public function getRequestID()
	{
		return $this->_id;
	}

	/*
	 *    アップロードファイルを取得
	 */
	public function getFile( Charcoal_String $userfile )
	{
		return new Charcoal_UploadedFile( $userfile );
	}

}

