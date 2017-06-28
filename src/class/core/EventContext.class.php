<?php
/**
* Context class for event
*
* PHP version 5
*
* @package    class.core
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_EventContext implements Charcoal_IEventContext
{
    /** @var Charcoal_Sandbox  */
    private $sandbox;
    
    /** @var Charcoal_IProcedure */
    private $procedure;

    /** @var Charcoal_IRequest */
    private $request;

    /** @var Charcoal_IResponse */
    private $response;

    /** @var Charcoal_IEvent */
    private $event;

    /** @var Charcoal_Session */
    private $session;

    /** @var  Charcoal_ITaskManager */
    private $task_manager;

    /**
     *    Construct
     *
     * @param Charcoal_Sandbox $sandbox
     * @param Charcoal_IProcedure $procedure
     * @param Charcoal_IRequest $request
     * @param Charcoal_IResponse $response
     * @param Charcoal_IEvent $event
     * @param Charcoal_Session $session
     * @param Charcoal_ITaskManager $task_manager
     */
    public function __construct( $sandbox, $procedure, $request, $response, $event, $session, $task_manager )
    {
        $this->sandbox = $sandbox;
        $this->procedure = $procedure;
        $this->request = $request;
        $this->response = $response;
        $this->event = $event;
        $this->session = $session;
        $this->task_manager = $task_manager;
    }

    /**
     *   returns sandbox
     *
     * @return Charcoal_Sandbox
     */
    public function getSandbox()
    {
        return $this->sandbox;
    }

    /**
     *    Get current procedure object
     *
     * @return Charcoal_IProcedure
     */
    public function getProcedure()
    {
        return $this->procedure;
    }

    /**
     *    Get current request object
     *
     *    @return Charcoal_IRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     *    Get current event object
     *
     *    @return Charcoal_IEvent
     */
    public function getEvent()
    {
        return $this->event;
    }
    /**
     *    Set current event object
     *
     * @param Charcoal_IEvent $event   Event object to set
     */
    public function setEvent( $event )
    {
        $this->event = $event;
    }
    
    /**
     *    Get current session object
     *
     * @return Charcoal_Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     *    Get current response object
     *
     * @return Charcoal_IResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     *    Get sandbox profile
     *
     * @return Charcoal_SandboxProfile
     */
    public function getProfile()
    {
        return $this->sandbox->getProfile();
    }

    /**
     *    Get sandbox environment
     *
     * @return Charcoal_IEnvironment         environment object
     */
    public function getEnvironment()
    {
        return $this->sandbox->getEnvironment();
    }

    /**
     *    Get debug mode
     *
     * @return boolean        TRUE means running in debug mode
     */
    public function isDebug()
    {
        return $this->sandbox->isDebug();
    }

    /**
     *    Create and configure an object
     *
     *    @param string $obj_path         object path to create
     *    @param string $type_name        type name of the object
     *    @param array $args             constructor arguments
     *    @param array $config           object configuration parameters
     *
     * @return Charcoal_CharcoalComponent        object instance
     */
    public function createObject( $obj_path, $type_name, $args = array(), $config = array() )
    {
        try{
            $object = $this->sandbox->createObject( $obj_path, $type_name, $args, $config );

            return $object;
        }
        catch( Exception $ex )
        {
            _catch( $ex );
            _throw( new Charcoal_EventContextException( __METHOD__ . '() failed.', $ex ) );
        }
        return null;
    }

    /**
     *    Create event
     *
     *    @param string $obj_path         object path to create
     *    @param array $args             constructor arguments
     *    @param array $config           object configuration parameters
     *
     * @return Charcoal_IEvent        object instance
     */
    public function createEvent( $obj_path, $args = array(), $config = NULL )
    {
        try{
            /* @var Charcoal_IEvent $event */
            $event = $this->sandbox->createEvent( $obj_path, $args, $config );

            return $event;
        }
        catch( Exception $ex )
        {
            _catch( $ex );
            _throw( new Charcoal_EventContextException( __METHOD__ . '() failed.', $ex ) );
        }
        return null;
    }

    /**
     * Create and configure a component
     *
     * @param string $obj_path         object path to create
     * @param array $args             constructor arguments
     * @param array $config           object configuration parameters
     *
     * @return Charcoal_ICharcoalComponent        component instance
     */
    public function getComponent( $obj_path, $args = array(), $config = NULL )
    {
        try{
            $component = $this->sandbox->getContainer()->getComponent( $obj_path, $args, $config );

            if ( $config ){
                $component->configure( $config );
            }

            return $component;
        }
        catch( Exception $ex )
        {
            _catch( $ex );
            _throw( new Charcoal_EventContextException( __METHOD__ . '() failed.', $ex ) );
        }
        return NULL;
    }


    /**
     *    Get cache data
     *
     * @param Charcoal_String $key                   string name to identify cached data
     * @param Charcoal_String $type_name_checked     checks type(class/interface) if not NULL
     *
     * @return mixed
     */
    public function getCache( $key, Charcoal_String $type_name_checked = NULL )
    {
        try{
            $type_name_checked = us($type_name_checked);

            $cached_data = Charcoal_Framework::getCache( $key );

            $type_check = $cached_data instanceof Charcoal_Object;
            if ( !$type_check ){
                $actual_type = get_class($cached_data);
                log_warning( "system, debug, cache", "cache", "cache type mismatch: expected=[Charcoal_Object] actual=[$actual_type]" );
                return FALSE;
            }

            if ( $cached_data !== FALSE && $type_name_checked !== NULL ){
                $type_check = $cached_data instanceof $type_name_checked;
                if ( !$type_check ){
                    $actual_type = get_class($cached_data);
                    log_warning( "system, debug, cache", "cache", "cache type mismatch: expected=[$type_name_checked] actual=[$actual_type]" );
                    return FALSE;
                }
            }

            return $cached_data;
        }
        catch( Exception $ex )
        {
            _catch( $ex );
            _throw( new Charcoal_EventContextException( __METHOD__ . '() failed.', $ex ) );
        }
        return NULL;
    }

    /**
     *    Set cache data
     *
     * @param Charcoal_String $key                   string name to identify cached data
     * @param Charcoal_Object $value                 cache data to save
     */
    public function setCache( $key, $value )
    {
        try{
            Charcoal_Framework::setCache( $key, $value );
        }
        catch( Exception $ex )
        {
            _catch( $ex );
            _throw( new Charcoal_EventContextException( __METHOD__ . '() failed.', $ex ) );
        }
    }


    /**
     * Get framework/project/application path
     *
     * @param string|Charcoal_String $virtual_path   virtual path including macro key like '%BASE_DIR%', '%WEBAPP_DIR%', etc.
     * @param string|Charcoal_String $filename
     *
     * @return string|Charcoal_String        full path string
     *
     * [macro keyword sample]
     *
     *      macro keyword       |                return value(real path)
     * -------------------------|------------------------------------------------
     *   %APPLICATION_DIR%      | (web_app_root)/webapp/(project_name)/app/(application name)/
     *   %PROJECT_DIR%          | (web_app_root)/webapp/(project_name)/
     *   %WEBAPP_DIR%           | (web_app_root)/webapp/
     *
     *
     * @see Charcoal_ResourceLocator
     *
     */
    public function getPath( $virtual_path, $filename = NULL )
    {
        return Charcoal_ResourceLocator::getPath( $this->getSandbox()->getEnvironment(), $virtual_path, $filename );
    }


    /**
     * Get framework/project/application file
     *
     * @param Charcoal_String $virtual_path          virtual path including macro key like '%BASE_DIR%', '%WEBAPP_DIR%', etc.
     * @param string|Charcoal_String $filename
     *
     * @return Charcoal_File        file object
     *
     * [macro keyword sample]
     *
     *      macro keyword        |                return value(real path)
     * ---------------------|------------------------------------------------
     *   %APPLICATION_DIR%    | (web_app_root)/webapp/(project_name)/app/(application name)/
     *   %PROJECT_DIR%        | (web_app_root)/webapp/(project_name)/
     *   %WEBAPP_DIR%        | (web_app_root)/webapp/
     *
     *
     * @see Charcoal_ResourceLocator
     *
     */
    public function getFile( $virtual_path, $filename = NULL )
    {
        return Charcoal_ResourceLocator::getFile( $this->getSandbox()->getEnvironment(), $virtual_path, $filename );
    }

    /**
     * load another module
     *
     * @param Charcoal_ObjectPath|Charcoal_String|string $module_path      module path to load
     */
    public function loadModule( $module_path )
    {
        Charcoal_ModuleLoader::loadModule( $this->sandbox, $module_path, $this->task_manager );
    }

    /**
     * set log level
     *
     * @param string|Charcoal_String  $log_level     new log level
     *
     * @return string                 old log level
     */
    public static function setLogLevel( $log_level )
    {
        return Charcoal_Framework::setLogLevel( $log_level );
    }

    /**
     *  add an event to task manager
     *
     * @param Charcoal_IEvent $event
     */
    public function pushEvent( $event )
    {
        $this->task_manager->pushEvent( $event );
    }

    /**
     *  returns event queue
     *
     * @return Charcoal_IEventQueue
     */
    public function getEventQueue()
    {
        return $this->task_manager->getEventQueue();
    }
}


