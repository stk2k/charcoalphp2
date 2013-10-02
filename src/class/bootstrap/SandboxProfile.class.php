<?php
/**
* 環境別の設定を扱うクラス
*
* PHP version 5
*
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_SandboxProfile extends Charcoal_ConfigPropertySet implements Charcoal_IProperties
{
	private $sandbox;

	/**
	 *  Constructor
	 */
	public function __construct( $sandbox )
	{
//		Charcoal_ParamTrait::checkSandbox( 1, $sandbox );

		$this->sandbox = $sandbox;

		parent::__construct();
	}

	/*
	 * グローバルの設定ファイルを読み込む
	 */
	public function load( $sandbox_name, $debug_mode )
	{
		$config_file = "{$sandbox_name}.profile.ini";

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
			$config = parse_ini_file($config_file,FALSE);
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

			_throw( new Charcoal_ProfileLoadingException( $config_file, $sandbox_name, $ex ) );
		}
	}

}

