<?php
/**
* HTTPリクエストをラップするクラス
*
* PHP version 5
*
* @package    requests
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_HttpRequest extends Charcoal_CharcoalObject implements Charcoal_IRequest
{
	private $_proc_path;
	private $_vars;
	private $_id;
	private $_cookie;
	private $_proc_key;

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_vars     = array();
		$this->_cookie   = new Charcoal_Cookie();

		// magic_quotes_gpc対策
		if ( get_magic_quotes_gpc() == 1 ){
			$get = array_map( array(CHARCOAL_CLASS_PREFIX . 'System','stripSlashes'), $_GET); 
			$post = array_map( array(CHARCOAL_CLASS_PREFIX . 'System','stripSlashes'), $_POST); 
		}
		else{
			$get = $_GET;
			$post = $_POST;
		}

		$a = array_merge( $get, $post );

		foreach( $a as $key => $value ){
			$this->_vars[ $key ] = $value;
		}

		// リクエストID
		$this->_id = Charcoal_System::hash();

		// プロシージャキー
		$this->_proc_key  = Charcoal_Profile::getString( s('PROC_KEY'), s('proc') );
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
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

	/*
	 *	キー一覧を取得
	 */
	public function getKeys() 
	{
		return array_keys($this->_vars);
	}

	/*
	 *    すべてのパラメータをハッシュマップで取得
	 */
	public function getAll()
	{
		return $this->_vars;
	}

	/*
	 *    キーがあるか
	 */
	public function keyExists( Charcoal_String $key )
	{
		$key = us($key);
		return isset($this->_vars[$key]);
	}

	/*
	 *   パラメータの取得
	 */
	public function get( Charcoal_String $key )
	{
		$key = us($key);
		return isset($this->_vars[$key]) ? $this->_vars[$key] : NULL;
	}

	/*
	 *    パラメータを設定
	 */
	public function set( Charcoal_String $key, $value )
	{
		$key = us($key);

		$this->_vars[$key] = $value;
	}

	/*
	 * パラメータを文字列として取得する
	 */
	public function getString( Charcoal_String $key, Charcoal_String $default_value = NULL )
	{
		$value = $this->get( $key );

		$value = us($value);

		// 文字列として不正ならデフォルト値を返す
		if ( NULL === $value || !is_string($value) ){
			return $default_value;
		}

		return s($value);
	}

	/*
	 * パラメータを配列として取得する
	 */
	public function getArray( Charcoal_String $key, Charcoal_Vector $default_value = NULL )
	{
		$value = $this->get( $key );

		$value = uv($value);

		// 配列値として不正ならデフォルト値を返す
		if ( NULL === $value || !is_array($value) ){
			return $default_value;
		}

		// 配列を返却
		return  v($value);
	}

	/*
	 * パラメータをブール値として取得する
	 */
	public function getBoolean( Charcoal_String $key, Charcoal_Boolean $default_value = NULL )
	{
		$value = $this->get( $key );

		$value = ub($value);

		if ( is_string($value) ){
			$value = (strlen($value) > 0 );
		}

		// ブール値として不正ならデフォルト値を返す
		if ( NULL === $value || !is_bool($value) ){
			return $default_value;
		}

		// ブール型にして返却
		return b($value);
	}

	/*
	 * パラメータを整数値として取得する
	 */
	public function getInteger( Charcoal_String $key, Charcoal_Integer $default_value = NULL )
	{
		$value = $this->get( $key );

		$value = ui($value);

		// 整数値として不正ならデフォルト値を返す
		if ( NULL === $value || !is_numeric($value) ){
			return $default_value;
		}

		// 整数型にして返却
		return i($value);
	}

	/*
	 * パラメータを浮動小数点数として取得する
	 */
	public function getFloat( Charcoal_String $key, Float $default_value = NULL )
	{
		$value = $this->get( $key );

		$value = uf($value);

		// 浮動小数点数として不正ならデフォルト値を返す
		if ( NULL === $value || !is_numeric($value) ){
			return $default_value;
		}

		// 浮動小数点数型にして返却
		return f($value);
	}

	/*
	 *	配列の全要素を追加
	 */
	public function setArray( array $array )
	{
		$this->_vars = array_merge( $this->_vars, $array );
	}

	/*
	 *	プロパティ配列の全要素を追加
	 */
	public function setProperties( Charcoal_Properties $data )
	{
		$this->_vars = array_merge( $this->_vars, $data->getAll() );
	}

	/*
	 *	プロパティ配列をマージ
	 */
	public function mergeProperties( Charcoal_Properties $data, Charcoal_Boolean $overwrite = NULL )
	{
		$overwrite = $overwrite ? $overwrite->isTrue() : FALSE;

		foreach( $data as $key => $value ){
			if ( !isset($this->_vars[$key]) || $overwrite ){
				$this->_vars[$key] = $value;
			}
		}
	}
}

