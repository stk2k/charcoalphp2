<?php
/**
* sand box
*
*    - AOP mode: No 
*    - Cache : No 
*    - Registry: File System
*
* PHP version 5
*
* @package    classes.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Sandbox
{
	private $sandbox_name;
	private $registry;
	private $codebase;
	private $container;
	private $environment;

	private $profile;
	private $debug;
	private $loaded;

	private $factory;
	private $config_provider;

	/**
	 *  Constructor
	 */
	public function __construct( $sandbox_name, $debug = NULL, $config = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $sandbox_name );
//		Charcoal_ParamTrait::checkBoolean( 2, $debug, TRUE );
//		Charcoal_ParamTrait::checkRawArray( 3, $config, TRUE );

		$this->sandbox_name = $sandbox_name;
		$this->debug = $debug ? ub($debug) : FALSE;

		$this->registry = isset($config['registry'])? $config['registry'] : new Charcoal_FileSystemRegistry( $this );
		$this->codebase = isset($config['codebase'])? $config['codebase'] : new Charcoal_PlainCodebase( $this );
		$this->container = isset($config['container'])? $config['container'] : new Charcoal_DIContainer( $this );
		$this->environment = isset($config['environment'])? $config['environment'] : $this->getDefaultEnvironment();

		$this->profile = isset($config['profile'])? $config['profile'] : new Charcoal_SandboxProfile( $this );
	}

	/**
	 * get proper environment for current run mode
	 * 
	 * @return Charcoal_IEnvironment         environment object
	 */
	private function getDefaultEnvironment()
	{
		switch( CHARCOAL_RUNMODE ){
		case 'http':
			return new Charcoal_HttpEnvironment();
		case 'shell':
			return new Charcoal_ShellEnvironment();
		}

		_throw( new Charcoal_IllegalRunModeException( CHARCOAL_RUNMODE ) );
	}

	/**
	 * returns debug flag
	 * 
	 * @return bool         TRUE if debug flag is on, otherwise FALSE
	 */
	public function isDebug()
	{
		return $this->debug;
	}

	/**
	 * load
	 */
	public function load()
	{
		$this->profile->load( 'default', $this->debug );
		$this->profile->load( $this->sandbox_name, $this->debug );

		$this->loaded = TRUE;

		return $this->profile;
	}

	/**
	 * get sandbox name
	 * 
	 * @return string         sandbox name
	 */
	public function getSandboxName()
	{
		return $this->sandbox_name;
	}

	/**
	 * get registry
	 * 
	 * @return Charcoal_IRegistry         registry object
	 */
	public function getRegistry()
	{
		return $this->registry;
	}

	/**
	 * get code base
	 * 
	 * @return Charcoal_ICodebase         codebase object
	 */
	public function getCodebase()
	{
		return $this->codebase;
	}

	/**
	 * get container
	 * 
	 * @return Charcoal_IContainer         container object
	 */
	public function getContainer()
	{
		return $this->container;
	}

	/**
	 * get environment
	 * 
	 * @return Charcoal_IEnvironment         environment object
	 */
	public function getEnvironment()
	{
		return $this->environment;
	}

	/**
	 * get container
	 * 
	 * @return Charcoal_IContainer         container object
	 */
	public function getProfile()
	{
		if ( !$this->loaded ){
			list( $file, $line ) = Charcoal_System::caller(1);
			_throw( new Charcoal_SandboxNotLoadedException( $file, $line ) );
		}
		return $this->profile;
	}

	/*
	 *  create event
	 *
	 *	@param Charcoal_String $obj_path         object path to create
	 *	@param Charcoal_Vector $args             constructor arguments
	 *
	 */
	public function createEvent( $obj_path, $args = NULL )
	{
		return $this->createObject( $obj_path, 'event', $args, 'Charcoal_IEvent' );
	}

	/*
	 *  create object
	 *
	 *	@param Charcoal_String $obj_path         object path to create
	 *	@param Charcoal_String $type_name        type name of the object
	 *	@param Charcoal_Vector $args             constructor arguments
	 *	@param Charcoal_String $interface        interface name which will be checked implements
	 *	@param Charcoal_String $default_class    default class name which will be used when class_name config property is not specified
	 *
	 */
	public function createObject( $obj_path, $type_name, $args = NULL, $interface = NULL, $default_class = NULL )
	{
//		Charcoal_ParamTrait::checkStringOrObjectPath( 1, $obj_path );
//		Charcoal_ParamTrait::checkString( 2, $type_name );
//		Charcoal_ParamTrait::checkVector( 3, $args, TRUE );
//		Charcoal_ParamTrait::checkStringOrObject( 4, 'Charcoal_Interface', $interface, TRUE );
//		Charcoal_ParamTrait::checkStringOrObject( 5, 'Charcoal_Class', $default_class, TRUE );

		if ( Charcoal_ParamTrait::isString( $obj_path, FALSE ) ){
			$obj_path = new Charcoal_ObjectPath( $obj_path );
		}

		if ( Charcoal_ParamTrait::isString( $interface, FALSE ) ){
			$interface = new Charcoal_Interface( $interface );
		}

		try{
			// load configure file
			$config = Charcoal_ConfigLoader::loadConfig( $this, $obj_path, $type_name );
			$config = new Charcoal_Config( $config );

			// get class name from configure file
			$class_name = $config->getString( 'class_name' );
			if ( $class_name && !$class_name->isEmpty() ){
				$klass = new Charcoal_Class( $class_name );
			}
			else{
				if ( $default_class ){
					if ( Charcoal_ParamTrait::isString( $default_class ) ){
						$default_class = new Charcoal_Class( $default_class );
					}
					$klass = $default_class;
				}
				else{
					_throw( new Charcoal_ClassNameEmptyException( "$obj_path/$type_name" ) );
				}
			}

			// constructor args
			$obj_name = $obj_path->getObjectName();

			// create instance
			$object = $klass->newInstance( $args );

			// confirm implementation of the instance
			if ( $interface ){
				$interface->checkImplements( $object );
			}

			// set properties
			$object->setObjectName( $obj_name );
			$object->setObjectPath( $obj_path );
			$object->setTypeName( $type_name );
			$object->setSandbox( $this );

			// configure object
			$object->configure( $config );

			// return created instance
			return $object;
		}
		catch ( Exception $e ) 
		{
			_catch( $e );

			// 上位にthrow
			_throw( new Charcoal_CreateObjectException($obj_path, $type_name, $e) );
		}
	}

	/**
	 *  create class loader
	 *  
	 *  @param string $obj_path        class loader's object path to create
	 *  
	 *  @return Charcoal_IClassLoader      class loader object
	 */
	public function createClassLoader( $obj_path )
	{
//		Charcoal_ParamTrait::checkStringOrObjectPath( 1, $obj_path );

		try{
			$obj_path = is_string($obj_path) ? new Charcoal_ObjectPath( $obj_path ) : $obj_path;

			// Configをロード
			$config = Charcoal_ConfigLoader::loadConfig( $this, $obj_path, 'class_loader' );
			$config = new Charcoal_Config( $config );

			// クラス名を取得
			$class_name = $config->getString( 'class_name' );
			if ( $class_name === NULL ){
				_throw( new Charcoal_ClassLoaderConfigException( $obj_path, 'class_name', 'mandatory' ) );
			}

			// ソースパスを取得
			$source_file = $config->getString( 'source_file' );
			if ( $source_file === NULL ){
				_throw( new Charcoal_ClassLoaderConfigException( 'source_file', 'mandatory' ) );
			}

			// ソースの取り込み
			$source_path = CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/apps/' . CHARCOAL_APPLICATION . '/classes/' . $source_file;
			if ( is_readable($source_path) ){
				include( $source_path );
			}
			else{
				$source_path = CHARCOAL_WEBAPP_DIR . '/' . CHARCOAL_PROJECT . '/classes/' . $source_file;
				if ( is_readable($source_path) ){
					include( $source_path );
				}
			}

			// クラスローダのインスタンス生成
			$klass = new Charcoal_Class( $class_name );
			$class_loader = $klass->newInstance();

			// インタフェース確認
			$interface = new Charcoal_Interface( 'Charcoal_IClassLoader' );
			$interface->checkImplements( $class_loader );

	//		log_info( 'system', "factory", "クラスローダ[" . us($object_path) . "]を作成しました。" );

			// ロードしたクラスローダを返却
			return $class_loader;
		}
		catch( Exception $ex )
		{
			_catch( $ex );

			_throw( new Charcoal_CreateClassLoaderException( $obj_path, $ex ) );
		}
	}

	/**
	 * get config provider
	 * 
	 * @return Charcoal_IConfigProvider     config provider
	 */
	public function getConfigProvider()
	{
		if ( $this->config_provider ){
			return $this->config_provider;
		}

		// 設定プロバイダクラス名
		$class_name = $this->profile->getString( 'CONFIG_PROVIDER_CLASS', CHARCOAL_CLASS_PREFIX . 'IniConfigProvider' );

		// 設定プロバイダオプション
		$options = $this->profile->getHashMap( 'CONFIG_PROVIDER_OPTIONS' );

		// create class object
		$klass = new Charcoal_Class( $class_name );

		// 設定プロバイダのインスタンス生成
		$provider = $klass->newInstance();

		$provider->setOptions( $options );

		// 生成したインスタンスがIConfigProviderインタフェースを実装しているか確認
		if ( !($provider instanceof Charcoal_IConfigProvider) ){
			// Invoke Exception
			_throw( new Charcoal_InterfaceImplementException( $class_name, CHARCOAL_CLASS_PREFIX . "IConfigProvider" ) );
		}

		$this->config_provider = $provider;

		// ロードした設定プロバイダを返却
		return $provider;
	}

}

