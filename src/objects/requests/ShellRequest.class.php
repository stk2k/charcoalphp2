<?php
/**
* シェルリからの起動引数を表現するクラス
*
* PHP version 5
*
* @package    requests
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ShellRequest extends Charcoal_CharcoalObject implements Charcoal_IRequest
{
	private $_obj_path;
	private $_data;
	private $_id;

	/*
	 *    コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();

		$argv = $_SERVER[ 'argv' ];
		$this->_data  = Charcoal_CommandLineUtil::parseParams( $argv );

		log_debug( "debug", "argv:" . print_r($this->_data,true) );

		$proc_key = Charcoal_Profile::getString( s('PROC_KEY') );
		$obj_path = $this->get( $proc_key );

		$this->_obj_path = new Charcoal_ObjectPath( s($obj_path) );

		$this->_id = strval(microtime(TRUE));
	}

	/*
	 *    プロシージャパスを取得
	 */
	public function getProcedurePath()
	{
		return $this->_obj_path;
	}

	/*
	 * リクエストIDを取得
	 */
	public function getRequestID()
	{
		return $this->_id;
	}

	/*
	 *    URLを取得
	 */
	public function getURL()
	{
		return $this->_url;
	}

	/*
	 *    キー一覧を取得
	 */
	public function getKeys()
	{
		return array_keys( $this->_data );
	}

	/**
	 *	Get all values with keys
	 *
	 * @return array
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
		return isset($this->_data[$key]);
	}

	/*
	 *    パラメータを取得
	 */
	public function get( Charcoal_String $key )
	{
		$key = us($key);
		return isset($this->_data[$key]) ? $this->_data[$key] : NULL;
	}

	/*
	 *    パラメータを設定
	 */
	public function set( Charcoal_String $key, $value )
	{
		$key = us($key);

		$this->_data[$key] = $value;
	}

	/*
	 *    文字列パラメータを取得
	 */
	public function getString( Charcoal_String $key, Charcoal_String $default_value = NULL )
	{
		$value = $this->get( $key );

		// 未設定なら空の文字列を返す
		if ( NULL === $value ){
			return $default_value;
		}

		// フォーマット確認
		if ( !is_string($value) ){
			_throw( new Charcoal_StringFormatException( $key ) );
		}

		return s($value);
	}


	/*
	 *    配列パラメータを取得
	 */
	public function getArray( Charcoal_String $key, Charcoal_Vector $default_value = NULL )
	{
		$value = $this->get( $key );

		// 未設定なら空の配列を返す
		if ( NULL === $value ){
			return $default_value;
		}

		// カンマで分割
		$array = explode( ',', $value );

		// 要素内の空白を削除
		foreach( $array as $_key => $value ){
			$value = trim($value);
			if ( strlen($value) == 0 ){
				unset( $array[$_key] );
			}
			else{
				$array[$_key] = us( $value );
			}
		}

		// フォーマット確認
		if ( !is_array($array) ){
			_throw( new Charcoal_ArrayFormatException( $key ) );
		}

		// 配列を返却
		return  v($array);
	}

	/*
	 * パラメータをブール値として取得する
	 */
	public function getBoolean( Charcoal_String $key, Charcoal_Boolean $default_value = NULL )
	{
		$value = $this->get( $key );

		// 未設定ならFALSEを返す
		if ( NULL === $value ){
			return $default_value !== NULL ? $default_value : b(FALSE);
		}

		if ( is_string($value) ){
			$value = (strlen($value) > 0 );
		}

		// フォーマット確認
		if ( !is_bool($value) ){
			_throw( new BooleanFormatException( $value, "key=[$key]" ) );
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

		// 未設定ならNULLを返す
		if ( NULL === $value ){
			return $default_value;
		}

		// フォーマット確認
		if ( !is_numeric($value) ){
			_throw( new IntegerFormatException( $value, "key=[$key]" ) );
		}

		// 整数型にして返却
		return (int)$value;
	}

	/*
	 * パラメータを浮動小数点数として取得する
	 */
	public function getFloat( Charcoal_String $key, Float $default_value = NULL )
	{
		$value = $this->get( $key );

		// 未設定ならNULLを返す
		if ( NULL === $value ){
			return $default_value ? $default_value : f(0.0);
		}

		// フォーマット確認
		if ( !is_numeric($value) ){
			_throw( new FloatFormatException( $value, "key=[$key]" ) );
		}

		// 浮動小数点数型にして返却
		return f($value);
	}

	/**
	 *	Set all array elements
	 *	
	 *	@param array $array   array data to set
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

