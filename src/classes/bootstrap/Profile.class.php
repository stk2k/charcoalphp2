<?php
/**
* 環境別の設定を扱うクラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Profile
{
	static $config_file;
	static $config;
	static $profile_name;

	/*
	 * キー一覧を取得
	 */
	public static function getKeys()
	{
		return array_keys( self::$config );
	}

	/*
	 * 設定ファイル名を取得
	 */
	public static function getConfigFile()
	{
		return self::$config_file;
	}

	/*
	 * 設定を配列で取得
	 */
	public static function getConfig()
	{
		return self::$config;
	}

	/*
	 * 値タイプのチェック
	 */
	public static function checkValueType( Charcoal_String $key, Charcoal_String $type )
	{
		$key  = $key->getValue();
		$type = $type->getValue();

		if (!isset(self::$config[$key])){
			// 判定できないのでTRUE
			return TRUE;
		}
		$value = self::$config[$key];

		switch( $type ){
			case 'S':	return TRUE;					break;
			case 'I':	return is_int(intval($value));	break;
			case 'B':	return is_bool((boolean) $value);				break;
			case 'A':	return (strpos($value,',') >= 0);		break;
		}

		return FALSE;
	}

	/*
	 * 必須チェック
	 */
	public static function checkMandatory( Charcoal_String $key )
	{
		$key  = $key->getValue();

		$set = isset(self::$config[$key]);

		return $set;
	}

	/*
	 * グローバル設定値を整数として取得
	 */
	public static function getInteger( Charcoal_String $key, Charcoal_Integer $default_value = NULL )
	{
		$key = $key->getValue();

		if ( !isset(self::$config[$key]) ){
			return $default_value;
		}
		$value = i(self::$config[$key]);
		return $value;
	}

	/*
	 * グローバル設定値を文字列として取得
	 */
	public static function getString( Charcoal_String $key, Charcoal_String $default_value = NULL )
	{
		$key = $key->getValue();

		// 未設定なら空文字を返す
		if (!isset(self::$config[$key])){
			return $default_value;
		}

		// 設定値を取得
		$value = self::$config[$key];

		// フォーマット確認
		if ( !is_string($value) ){
			_throw( new Charcoal_StringFormatException( $key ) );
		}

		$value = s($value);

		// 返却
		return Charcoal_ResourceLocator::processMacro($value);
	}

	/*
	 * グローバル設定値をブール値として取得
	 */
	public static function getBoolean( Charcoal_String $key, Charcoal_Boolean $default_value = NULL  )
	{
		$key = $key->getValue();

		if ( !isset(self::$config[ $key ]) ){
			return $default_value;
		}

		// 設定値を取得
		$value = self::$config[ $key ];

		if ( is_string($value) ){
			$value = (strlen($value) > 0 );
		}

		// フォーマット確認
		if ( !is_bool($value) ){
			_throw( new Charcoal_BooleanFormatException( $key ) );
		}

		// ブール型にして返却
		return b($value);
	}

	/*
	 * グローバル設定値を少数値として取得
	 */
	public static function getFloat( Charcoal_String $key, CharcoalFloat $default_value = NULL  )
	{
		$key = $key->getValue();

		if ( !isset(self::$config[ $key ]) ){
			return $default_value;
		}

		// 設定値を取得
		$value = self::$config[ $key ];

		// フォーマット確認
		if ( !is_numeric($value) ){
			_throw( new Charcoal_FloatFormatException( $key ) );
		}

		// ブール型にして返却
		return f($value);
	}


	/*
	 * グローバル設定値を配列として取得
	 */
	public static function getArray( Charcoal_String $key, Charcoal_Vector $default_value = NULL )
	{
		$key = $key->getValue();

		// 未設定なら空の配列を返す
		if ( !isset(self::$config[ $key ]) ){
			return $default_value;
		}

		// 設定値を取得
		$value = self::$config[ $key ];

		// カンマで分割
		$array = explode( ',', $value );

		// 要素内の空白を削除
		foreach( $array as $key => $value ){
			$array[$key] = trim($value);
		}

		// 要素が１つで空ならデフォルト値を返す
		if ( count($array) == 1 && $array[0] === '' ){
			return $default_value;
		}

		// フォーマット確認
		if ( !is_array($array) ){
			_throw( new Charcoal_ArrayFormatException( $key ) );
		}

		// 配列を返却
		return  v($array);
	}

	/*
	 * グローバル設定値を連想配列として取得
	 */
	public static function getProperties( Charcoal_String $key, Charcoal_Properties $default_value = NULL )
	{
		$key = $key->getValue();

		// 未設定なら空の配列を返す
		if ( !isset(self::$config[ $key ]) ){
			return $default_value;
		}

		// 設定値を取得
		$value = self::$config[ $key ];

		// カンマで分割
		$array = explode( ',', $value );

		// 要素内の空白を削除
		foreach( $array as $key => $value ){
			$array[$key] = trim($value);
		}

		// 要素が１つで空ならデフォルト値を返す
		if ( count($array) == 1 && $array[0] === '' ){
			return $default_value;
		}

		// フォーマット確認
		if ( !is_array($array) ){
			_throw( new Charcoal_ArrayFormatException( $key ) );
		}

		$ret = NULL;
		foreach( $array as $key_value ){
			$pos = strpos($key_value,":");
			if ( $pos !== FALSE ){
				$key = substr($key_value,0,$pos);
				$value = substr($key_value,$pos+1);
				if ( strlen($key) ){
					$ret[$key] = $value;
				}
				else{
					$ret[] = $value;
				}
			}
			else{
				$ret[] = $key_value;
			}
		}

		// 配列を返却
		return  p($ret);
	}

	/*
	 * グローバルの設定ファイルを読み込む
	 */
	public static function load( $profile_name, $debug_mode )
	{
		try{
			// get profile directory path
			$profile_dir = Charcoal_ResourceLocator::getApplicationFile( s('config/profiles') );

			// make config file object
			$config_file = "{$profile_name}.profile.ini";
			$config_file = new Charcoal_File( s($config_file), $profile_dir );
			self::$config_file = $config_file;

			// check if profile directory exists
			if ( !$profile_dir->exists() ){
				if ( $debug_mode )	echo "profile directory not exists: [$profile_dir]" . eol();
				log_error( "debug,config,profile", "profile directory not exists: [$profile_dir]" );
				_throw( new Charcoal_ProfileDirectoryNotFoundException( $profile_dir ) );
			}

			// throw exception when config file is not found
			if ( !$config_file->exists() || !$config_file->canRead() ){
				if ( $debug_mode )	echo "profile config file not exists or not readable: [$config_file]" . eol();
				log_error( "debug,config,profile", "profile config file not exists or not readable: [$config_file]" );
				_throw( new Charcoal_ProfileConfigFileNotFoundException( $config_file ) );
			}

			// parse config file
//			log_debug( "debug,config,profile", "profile", "parsing config file: [$config_file]" );
			$config_file = $config_file->getAbsolutePath();
			if ( $debug_mode )	echo "executing parse_ini_file: [$config_file]" . eol();
			$config = parse_ini_file($config_file,FALSE);
			if ( $debug_mode )	echo "executed parse_ini_file: " . ad($config) . eol();
//			log_debug( "profile", "profile", "parse_ini_file: " . print_r($config,TRUE) );

			// プロファイル名
			self::$profile_name = $profile_name;
//			log_debug( "debug,config,profile", "profile", "profile name: $profile_name" );

			// 設定を保存
			if ( !self::$config || !is_array(self::$config) ){
				self::$config = array();
			}
			self::$config = array_merge( self::$config, $config );
//			log_debug( "debug,config,profile", "profile", "marged profile:" . print_r($config,TRUE) );

		}
		catch( Exception $ex )
		{
//			log_debug( "system,error,debug", "catch $e" );
			_catch( $ex );

			$config_file  = new Charcoal_File( s(self::$config_file) );
			_throw( new Charcoal_ProfileLoadingException( $config_file, self::$profile_name, $ex ) );
		}
	}

}

