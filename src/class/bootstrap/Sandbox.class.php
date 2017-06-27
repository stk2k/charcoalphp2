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

use EventStream\EventStream;
use EventStream\Emitter\WildCardEventEmitter;

class Charcoal_Sandbox
{
    private $registry;
    private $codebase;
    private $container;
    private $environment;
    private $loaded;

    /** @var Charcoal_HashMap  */
    private $profile;

    /** @var bool  */
    private $debug;

    /** @var Charcoal_IConfigProvider */
    private $config_provider;
    
    /** @var Charcoal_CoreHookEventSource */
    private $core_hook_source;
    
    /** @var EventStream */
    private $core_hook_stream;

    /**
     *  Constructor
     *
     * @param boolean|NULL $debug
     * @param array $config
     * @param callable $core_hook
     */
    public function __construct( $debug = false, $config = null, $core_hook = null )
    {
        $this->debug = ub($debug);

        $this->registry = isset($config['registry'])? $config['registry'] : new Charcoal_FileSystemRegistry( $this );
        $this->codebase = isset($config['codebase'])? $config['codebase'] : new Charcoal_PlainCodebase( $this );
        $this->container = isset($config['container'])? $config['container'] : new Charcoal_DIContainer( $this );
        $this->environment = isset($config['environment'])? $config['environment'] : $this->getDefaultEnvironment();

        $this->profile = isset($config['profile'])? $config['profile'] : new Charcoal_SandboxProfile( $this );
    
        $this->core_hook_source = isset($config['core_hook_source'])? $config['core_hook_source'] : new Charcoal_CoreHookEventSource( $this );
        $this->core_hook_stream = new EventStream( $this->core_hook_source, new WildCardEventEmitter() );
    
        if ($core_hook){
            $this->core_hook_stream->listen('*.*', $core_hook);
        }
        
        $this->core_hook_stream->push( 'sandbox.created', CHARCOAL_RUNMODE, true );
    }
    
    /**
     * destruct instance
     */
    public function terminate()
    {
        $this->core_hook_stream->push( 'sandbox.terminated', CHARCOAL_RUNMODE, true );
    }
    
    /**
     * get proper environment for current run mode
     *
     * @return Charcoal_IEnvironment         environment object
     */
    private function getDefaultEnvironment()
    {
        $env = NULL;
        switch( CHARCOAL_RUNMODE ){
        case 'http':
            $env = new Charcoal_HttpEnvironment();
            break;
        case 'shell':
            $env = new Charcoal_ShellEnvironment();
            break;
        default:
            _throw( new Charcoal_IllegalRunModeException( CHARCOAL_RUNMODE ) );
            break;
        }
        return $env;
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
     * load sandbox
     *
     * @return Charcoal_SandboxProfile
     */
    public function load()
    {
//        try{
            $this->profile->load( $this->debug, 'default' );
            $this->profile->load( $this->debug, CHARCOAL_PROFILE );
//        }
//        catch( Exception $e ){
//            _catch( $e );
//        }

        $this->loaded = TRUE;

        return $this->profile;
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
     * get core hook event stream
     *
     * @return EventStream
     */
    public function getCoreHookEventStream()
    {
        return $this->core_hook_stream;
    }

    /*
     *  create event
     *
     *    @param Charcoal_String $obj_path         object path to create
     *    @param Charcoal_Vector $args             constructor arguments
     *    @param array $config                     object configuration parameters
     *
     */
    public function createEvent( $obj_path, $args = NULL, $config = array() )
    {
        return $this->createObject( $obj_path, 'event', $args, $config, 'Charcoal_IEvent' );
    }

    /*
     *  create object
     *
     *    @param Charcoal_String $obj_path         object path to create
     *    @param Charcoal_String $type_name        type name of the object
     *    @param array $args                       constructor arguments
     *    @param array $config                     object configuration parameters
     *    @param Charcoal_String $interface        interface name which will be checked implements
     *    @param Charcoal_String $default_class    default class name which will be used when class_name config property is not specified
     *
     */
    public function createObject( $obj_path, $type_name, $args = NULL, $config = NULL, $interface = NULL, $default_class = NULL )
    {
        $object = NULL;

        if ( is_string($obj_path) || $obj_path instanceof Charcoal_String ){
            $obj_path = new Charcoal_ObjectPath( $obj_path );
        }

        if ( is_string($interface) || $interface instanceof Charcoal_String ){
            $interface = new Charcoal_Interface( $interface );
        }

        try{
            $object_path_string = $obj_path->getObjectPathString();
    
            // load configure file
            $config_default = Charcoal_ConfigLoader::loadConfig( $this->registry, $object_path_string, $type_name );
    
            $config = is_array($config) ? array_merge( $config_default, $config ) : $config_default;

            // get class name from configure file
            $class_name = isset($config['class_name']) ? $config['class_name'] : NULL;
            
            $klass = NULL;
            if ( !empty($class_name) ){
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

            /** @var Charcoal_CharcoalComponent $object */
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
    
            // configure instantiated object
            $object->configure( $config );
        }
        catch ( Exception $e )
        {
            _catch( $e );

            // 上位にthrow
            _throw( new Charcoal_CreateObjectException($obj_path, $type_name, $e) );
        }
            /** @noinspection PhpUndefinedClassInspection */
        catch ( Throwable $e )
        {
            _catch( $e );

            // 上位にthrow
            _throw( new Charcoal_CreateObjectException($obj_path, $type_name, $e) );
        }

        // return created instance
        return $object;
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
        $class_loader = NULL;

        try{
            $obj_path = is_string($obj_path) ? new Charcoal_ObjectPath( $obj_path ) : $obj_path;
    
            // load configure file
            $config = Charcoal_ConfigLoader::loadConfig( $this->registry, $obj_path, 'class_loader' );
    
            // get class name from configure file
            $class_name = isset($config['class_name']) ? $config['class_name'] : NULL;
            if ( empty($class_name) ){
                _throw( new Charcoal_ClassLoaderConfigException( $obj_path, 'class_name', 'mandatory' ) );
            }

            // project directory
            $project_dir = Charcoal_ResourceLocator::getProjectPath();

            // read class loader file
            $source_path = $project_dir . '/app/' . CHARCOAL_APPLICATION . '/class/class_loader/' . $class_name . '.class.php';
            if ( is_readable($source_path) ){
                /** @noinspection PhpIncludeInspection */
                include( $source_path );
            }
            else{
                $source_path = $project_dir . '/class/class_loader/' . $class_name . '.class.php';
                if ( is_readable($source_path) ){
                    /** @noinspection PhpIncludeInspection */
                    include( $source_path );
                }
            }

            // instantiate class loader
            $klass = new Charcoal_Class( $class_name );

            /** @var Charcoal_IClassLoader $class_loader */
            $class_loader = $klass->newInstance();

            $class_loader->setSandbox( $this );

            // confirm inprements of class loader interface
            $interface = new Charcoal_Interface( 'Charcoal_IClassLoader' );
            $interface->validateImplements( $class_loader );

        }
        catch( Exception $ex )
        {
            _catch( $ex );

            _throw( new Charcoal_CreateClassLoaderException( $obj_path, $ex ) );
        }
        return $class_loader;
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

        /** @var Charcoal_IConfigProvider $provider */
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

