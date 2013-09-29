<?php
/**
* request class for http
*
* PHP version 5
*
* @package    objects.requests
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_HttpRequest extends Charcoal_AbstractRequest
{
	private $cookie;

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
		$this->cookie = $use_cookie ? new Charcoal_CookieReader() : NULL;
	}

	/*
	 *  Get all cookie values as array
	 *
	 * @return array
	 */
	public function getCookies()
	{
		return $this->cookie->toArray();
	}

	/*
	 *  Get cookie value
	 *
	 * @param string $name   cookie name to get
	 *
	 * @return Charcoal_String
	 */
	public function getCookie( $name )
	{
		$name = us($name);
		return $this->cookie->getValue( $name );
	}

	/*
	 *  Set cookie value
	 *
	 * @param string $name     cookie name to set
	 * @param string $value   cookie value to set
	 *
	 * @return Charcoal_String
	 */
	public function setCookie( $name, $value )
	{
		$name = us($name);
		$this->cookie->setValue( $name, $value );
	}

	/*
	 *    アップロードファイルを取得
	 */
	public function getFile( $userfile )
	{
		return new Charcoal_UploadedFile( $userfile );
	}

}

