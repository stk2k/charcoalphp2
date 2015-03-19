<?php
/**
* request class for http
*
* PHP version 5
*
* @package    objects.requests
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_HttpRequest extends Charcoal_AbstractRequest
{
	private $cookie;
	private $method;


	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();

		// magic_quotes_gpc対策
		if ( get_magic_quotes_gpc() == 1 ){
			$get = Charcoal_System::stripSlashes( $_GET ); 
			$post = Charcoal_System::stripSlashes( $_POST ); 
		}
		else{
			$get = $_GET;
			$post = $_POST;
		}

		// detext HTTP method
		$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
		switch( $method ){
		case 'POST':
			$this->method = Charcoal_EnumHttpMethod::HTTP_POST;
			break;
		case 'GET':
			$this->method = Charcoal_EnumHttpMethod::HTTP_GET;
			break;
		case 'PUT':
			$this->method = Charcoal_EnumHttpMethod::HTTP_PUT;
			break;
		case 'DELETE':
			$this->method = Charcoal_EnumHttpMethod::HTTP_DELETE;
			break;
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
		$use_cookie  = $this->getSandbox()->getProfile()->getBoolean( 'USE_COOKIE', FALSE );
		$this->cookie = $use_cookie ? new Charcoal_CookieReader() : NULL;
	}

	/*
	 *  Get all cookie values as array
	 *
	 * @return array
	 */
	public function getCookies()
	{
		return $this->cookie->getAll();
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
		return isset($_FILES[us($userfile)]) ? new Charcoal_UploadedFile( $userfile ) : NULL;
	}

	/**
	 * Get as json value
	 *
	 * @param string $key             key string for hash map
	 * @param string $default_value   default value
	 *
	 * @return string
	 */
	public function getJson( $key, $default_value = NULL )
	{
		Charcoal_ParamTrait::validateString( 1, $key );
		Charcoal_ParamTrait::validateString( 2, $default_value, TRUE );

		$key = us($key);
		$value = parent::getString( $key, $default_value );

		log_debug( "debug", "caller: " . print_r(Charcoal_System::caller(),true) );
		log_debug( "debug", "json_decode: $value" );

		$decoded = json_decode( us($value), true );

		log_debug( "debug", "decoded: " . print_r($decoded,true) );

		if ( $decoded === NULL ){
			_throw( new Charcoal_JsonDecodingException($value) );
		}

		return $decoded;
	}

}

