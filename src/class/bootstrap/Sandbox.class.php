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
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_Sandbox
{
	private $sandbox_name;
	private $registry;
	private $codebase;
	private $container;
	private $environment;
	private $registry_access_log;

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
//		Charcoal_ParamTrait::validateString( 1, $sandbox_name );
//		Charcoal_ParamTrait::validateBoolean( 2, $debug, TRUE );
//		Charcoal_ParamTrait::validatRawArray( 3, $config, TRUE );

		$this->sandbox_name = $sandbox_name;
		$this->debug = $debug ? ub($debug) : FALSE;

		$this->registry = isset($config['registry'])? $config['registry'] : new Charcoal_FileSystemRegistry( $this );
		$this->codebase = isset($config['codebase'])? $config['codebase'] : new Charcoal_PlainCodebase( $this );
		$this->container = isset($config['container'])? $config['container'] : new Charcoal_DIContainer( $this );
		$this->environment = isset($config['environment'])? $config['environment'] : $this->getDefaultEnvironment();

		$this->profile = isset($config['profile'])? $config['profile'] : new Charcoal_SandboxProfile( $this );

		$this->registry_access_log = new Charcoal_RegistryAccessLog( $this );
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
	 * set debug flag
	 * 
	 * @param bool $debug         TRUE if debug flag is on, otherwise FALSE
	 */
	public function setDebug( $debug )
	{
		$this->debug = $debug;
	}

	/**
	 * returns the sanbox is loaded
	 * 
	 * @return bool         TRUE if sandbox is loaded
	 */
	public function isLoaded()
	{
		return $this->loaded;
	}

	/**
	 * returns sandbox name
	 * 
	 * @return string         sandbox name
	 */
	public function getName()
	{
		return $this->sandbox_name;
	}

	/**
	 * load
	 */
	public function load()
	{
//		try{
			$this->profile->load( $this->debug, 'default' );
			$this->profile->load( $this->debug, $this->sandbox_name );
//		}
//		catch( Exception $e ){
//			_catch( $e );
//		}

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
	 * @return Charcoal_ICodebase         sandbox codebase object
	 */
	public function getCodebase()
	{
		return $this->codebase;
	}

	/**
	 * get container object
	 * 
	 * @return Charcoal_IContainer         sandbox container object
	 */
	public function getContainer()
	{
		return $this->container;
	}

	/**
	 * get environment object
	 * 
	 * @return Charcoal_IEnvironment         sandbox environment object
	 */
	public function getEnvironment()
	{
		return $this->environment;
	}

	/**
	 * get profile object
	 * 
	 * @return Charcoal_SandboxProfile         sandbox profile object
	 */
	public function getProfile()
	{
		if ( !$this->loaded ){
			list( $file, $line ) = Charcoal_System::caller(1);
			_throw( new Charcoal_SandboxNotLoadedException( $file, $line ) );
		}
		return $this->profile;
	}

	/**
	 * get registry access log object
	 * 
	 * @return Charcoal_RegistryAccessLog         registry access log object
	 */
	public function getRegistryAccessLog()
	{
		return $this->registry_access_log;
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
//		Charcoal_ParamTrait::validateStringOrObjectPath( 1, $obj_path );
//		Charcoal_ParamTrait::validateString( 2, $type_name );
//		Charcoal_ParamTrait::validateVector( 3, $args, TRUE );
//		Charcoal_ParamTrait::validateStringOrObject( 4, 'Charcoal_Interface', $interface, TRUE );
//		Charcoal_ParamTrait::validateStringOrObject( 5, 'Charcoal_Class', $default_class, TRUE );

		if ( is_string($obj_path) || $obj_path instanceof Charcoal_String ){
			$obj_path = new Charcoal_ObjectPath( $obj_path );
		}

		if ( is_string($interface) || $interface instanceof Charcoal_String ){
			$interface = new Charcoal_Interface( $interface );
		}

		try{
			$object_path_string = $obj_path->getObjectPathString();

			// load configure file
			$config = Charcoal_ConfigLoader::loadConfig( $this, $object_path_string, $type_name );

			$config = new Charcoal_Config( $this->getEnvironment(), $config );

			// get class name from configure file
			$class_name = $config->getString( 'class_name' );

			if ( $class_name && !empty($class_name) ){
				$klass = new Charcoal_Class( $class_name );
			}
			else{
				if ( $default_class ){
					if ( is_string($default_class) || $default_class instanceof Charcoal_Class ){
						$default_class = new Charcoal_Class( $default_class );
					}
					$klass = $default_class;
				}
				else{
					_throw( new Charcoal_ClassNameEmptyException( "$object_path_string/$type_name" ) );
				}
			}

			// constructor args
			$obj_name = $obj_path->getObjectName();

			// create instance
			$object = $klass->newInstance( $args );

			// confirm implementation of the instance
			if ( $interface ){
				$interface->validateImplements( $object );
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
//		Charcoal_ParamTrait::validateStringOrObjectPath( 1, $obj_path );

		try{
			$obj_path = is_string($obj_path) ? new Charcoal_ObjectPath( $obj_path ) : $obj_path;

			// Configをロード
			$config = Charcoal_ConfigLoader::loadConfig( $this, $obj_path, 'class_loader' );
			$config = new Charcoal_Config( $this->environment, $config );

			// クラス名を取得
			$class_name = $config->getString( 'class_name' );
			if ( $class_name === NULL ){
				_throw( new Charcoal_ClassLoaderConfigException( $obj_path, 'class_name', 'mandatory' ) );
			}
			$class_name = us($class_name);

			// project directory
			$project_dir = Charcoal_ResourceLocator::getProjectPath();

			// ソースの取り込み
			$source_path = $project_dir . '/app/' . CHARCOAL_APPLICATION . '/class/class_loader/' . $class_name . '.class.php';
			if ( is_readable($source_path) ){
				include( $source_path );
			}
			else{
				$source_path = $project_dir . '/class/class_loader/' . $class_name . '.class.php';
				if ( is_readable($source_path) ){
					include( $source_path );
				}
			}

			// クラスローダのインスタンス生成
			$klass = new Charcoal_Class( $class_name );
			$class_loader = $klass->newInstance();

			$class_loader->setSandbox( $this );

			// インタフェース確認
			$interface = new Charcoal_Interface( 'Charcoal_IClassLoader' );
			$interface->validateImplements( $class_loader );

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
		$class_name = $this->profile->getString( 'CONFIG_PROVIDER_CLASS', 'Charcoal_IniConfigProvider' );

		// 設定プロバイダオプション
		$options = $this->profile->getHashMap( 'CONFIG_PROVIDER_OPTIONS' );

		// create class object
		$klass = new Charcoal_Class( $class_name );

		// 設定プロバイダのインスタンス生成
		$provider = $klass->newInstance();

		// set properties
		$provider->setSandbox( $this );

		// configure object
		$provider->setOptions( $options );

		// 生成したインスタンスがIConfigProviderインタフェースを実装しているか確認
		if ( !($provider instanceof Charcoal_IConfigProvider) ){
			// Invoke Exception
			_throw( new Charcoal_InterfaceImplementException( $class_name, 'Charcoal_IniConfigProvider' ) );
		}

		$this->config_provider = $provider;

		// ロードした設定プロバイダを返却
		return $provider;
	}

}

