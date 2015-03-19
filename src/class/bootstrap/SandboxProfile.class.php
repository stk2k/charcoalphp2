<?php
/**
* 環境別の設定を扱うクラス
*
* PHP version 5
*
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_SandboxProfile extends Charcoal_ConfigPropertySet implements Charcoal_IProperties
{
	private $sandbox;

	/**
	 *  Constructor
	 */
	public function __construct( $sandbox )
	{
//		Charcoal_ParamTrait::validateSandbox( 1, $sandbox );

		$this->sandbox = $sandbox;

		parent::__construct( $sandbox->getEnvironment() );
	}

	/*
	 * グローバルの設定ファイルを読み込む
	 */
	public function load( $debug_mode, $profile_name )
	{
		$config_file = "{$profile_name}.profile.ini";

		try{
			// get profile directory path
			$profile_dir = Charcoal_ResourceLocator::getApplicationFile( 'config/profile' );

			// make config file object
			$config_file = new Charcoal_File( $config_file, $profile_dir );

			// check if profile directory exists
			if ( !$profile_dir->exists() ){
				if ( $debug_mode )	echo "profile directory not exists: [$profile_dir]" . eol();
				log_error( "debug,config,profile", "profile directory not exists: [$profile_dir]" );
				_throw( new Charcoal_ProfileDirectoryNotFoundException( $profile_dir ) );
			}

			// throw exception when config file is not found
			if ( !$config_file->isFile() || !$config_file->canRead() ){
				if ( $debug_mode )	echo "profile config file not exists or not readable: [$config_file]" . eol();
				log_error( "debug,config,profile", "profile config file not exists or not readable: [$config_file]" );
				_throw( new Charcoal_ProfileConfigFileNotFoundException( $config_file ) );
			}

			// parse config file
//			log_debug( "debug,config,profile", "profile", "parsing config file: [$config_file]" );
			$config_file = $config_file->getAbsolutePath();
			if ( $debug_mode )	echo "executing parse_ini_file: [$config_file]" . eol();
			$config = @parse_ini_file($config_file,FALSE);

			if ( $config === FALSE ){
				if ( $debug_mode )	echo "profile config file format error: [$config_file]" . eol();
				log_error( "debug,config,profile", "profile config file format error: [$config_file]" );
				_throw( new Charcoal_ProfileConfigFileFormatException( $config_file ) );
			}
			if ( $debug_mode )	echo "executed parse_ini_file: " . ad($config) . eol();
//			log_debug( "profile", "profile", "parse_ini_file: " . print_r($config,TRUE) );

			// 設定を保存
			parent::mergeArray( $config );
//			log_debug( "debug,config,profile", "profile", "marged profile:" . print_r($config,TRUE) );

		}
		catch( Exception $ex )
		{
//			log_debug( "system,error,debug", "catch $e" );
			_catch( $ex );

			_throw( new Charcoal_ProfileLoadingException( $config_file, $profile_name, $ex ) );
		}
	}

	/**
	 * Get as string value
	 *
	 * @param string $key             key string for hash map
	 * @param string $default_value   default value
	 * @param bool $process_macro     if TRUE, value will be replaced by keywords, FALSE otherwise
	 *
	 * @return string
	 */
	public function getString( $key, $default_value = NULL, $process_macro = FALSE )
	{
		try{
			return parent::getString( $key, $default_value, $process_macro );
		}
		catch( Exception $ex ){
			_catch( $ex );

			_throw( new Charcoal_ProfileConfigException( $key, __METHOD__ . '() failed.', $ex ) );
		}
	}

	/**
	 * Get as json value
	 *
	 * @param string $key             key string for hash map
	 * @param string $default_value   default value
	 * @param bool $process_macro     if TRUE, value will be replaced by keywords, FALSE otherwise
	 *
	 * @return string
	 */
	public function getJson( $key, $default_value = NULL, $process_macro = FALSE )
	{
		try{
			return parent::getJson( $key, $default_value, $process_macro );
		}
		catch( Exception $ex ){
			_catch( $ex );

			_throw( new Charcoal_ProfileConfigException( $key, __METHOD__ . '() failed.', $ex ) );
		}
	}

	/**
	 * Get as array value
	 *
	 * @param string $key             key string for hash map
	 * @param array $default_value   default value
	 * @param bool $process_macro     if TRUE, value will be replaced by keywords, FALSE otherwise
	 *
	 * @return array
	 */
	public function getArray( $key, $default_value = NULL, $process_macro = FALSE )
	{
		try{
			return parent::getArray( $key, $default_value, $process_macro );
		}
		catch( Exception $ex ){
			_catch( $ex );

			_throw( new Charcoal_ProfileConfigException( $key, __METHOD__ . '() failed.', $ex ) );
		}
	}

	/**
	 * Get as boolean value
	 *
	 * @param string $key             key string for hash map
	 * @param bool $default_value   default value
	 *
	 * @return bool
	 */
	public function getBoolean( $key, $default_value = NULL )
	{
		try{
			return parent::getBoolean( $key, $default_value );
		}
		catch( Exception $ex ){
			_catch( $ex );

			_throw( new Charcoal_ProfileConfigException( $key, __METHOD__ . '() failed.', $ex ) );
		}
	}

	/**
	 * Get as integer value
	 *
	 * @param string $key             key string for hash map
	 * @param int $default_value   default value
	 *
	 * @return int
	 */
	public function getInteger( $key, $default_value = NULL )
	{
		try{
			return parent::getInteger( $key, $default_value );
		}
		catch( Exception $ex ){
			_catch( $ex );

			_throw( new Charcoal_ProfileConfigException( $key, __METHOD__ . '() failed.', $ex ) );
		}
	}

	/**
	 *  Get element value as float
	 *
	 * @param string $key            Key string to get
	 * @param float $default_value   default value
	 *
	 * @return float
	 */
	public function getFloat( $key, $default_value = NULL )
	{
		try{
			return parent::getFloat( $key, $default_value );
		}
		catch( Exception $ex ){
			_catch( $ex );

			_throw( new Charcoal_ProfileConfigException( $key, __METHOD__ . '() failed.', $ex ) );
		}
	}

	/**
	 *  Get element value as file size
	 *
	 * @param string $key            Key string to get
	 * @param float $default_value   default value
	 *
	 * @return integer
	 */
	public function getSize( $key, $default_value = NULL )
	{
		try{
			return parent::getSize( $key, $default_value );
		}
		catch( Exception $ex ){
			_catch( $ex );

			_throw( new Charcoal_ProfileConfigException( $key, __METHOD__ . '() failed.', $ex ) );
		}
	}

}

