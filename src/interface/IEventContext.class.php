<?php
/**
* Event Context Interface
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IEventContext
{
    /**
     *    Get current procedure object
     *
     * @return Charcoal_IProcedure
     */
    public function getProcedure();

    /**
     *    Get current request object
     *
     *    @return Charcoal_IRequest
     */
    public function getRequest();

    /**
     *    Get current event object
     *
     *    @return Charcoal_IEvent
     */
    public function getEvent();

    /**
     *    Set current event object
     *
     * @param Charcoal_IEvent $event   Event object to set
     */
    public function setEvent( $event );

    /**
     *    Get current sequence object
     *
     * @return Charcoal_ISequence
     */
    public function getSequence();

    /**
     *    Get current response object
     *
     * @return Charcoal_IResponse
     */
    public function getResponse();

    /**
     *    Get sandbox profile
     *
     * @return Charcoal_SandboxProfile
     */
    public function getProfile();

    /**
     *    Get sandbox environment
     *
     * @return Charcoal_IEnvironment         environment object
     */
    public function getEnvironment();

    /**
     *    Get debug mode
     *
     * @return boolean        TRUE means running in debug mode
     */
    public function isDebug();


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
    public function createObject( $obj_path, $type_name, $args = array(), $config = NULL );

    /**
     *    Create event
     *
     *    @param string $obj_path         object path to create
     *    @param array $args             constructor arguments
     *    @param array $config           object configuration parameters
     *
     * @return Charcoal_CharcoalObject        object instance
     */
    public function createEvent( $obj_path, $args = array(), $config = NULL );

    /**
     *    Create condig
     *
     *    @param array $config           object configuration parameters
     *
     * @return Charcoal_Config        config object
     */
    public function createConfig( array $config = array() );

    /**
     * Create and configure a component
     *
     * @param string $obj_path         object path to create
     * @param array $args             constructor arguments
     * @param array $config           object configuration parameters
     *
     * @return Charcoal_IComponent        component instance
     */
    public function getComponent( $obj_path, $args = array(), $config = NULL );

    /**
     *    Get cache data
     *
     * @param Charcoal_String $key                   string name to identify cached data
     * @param Charcoal_String $type_name_checked     checks type(class/interface) if not NULL
     */
    public function getCache( $key, Charcoal_String $type_name_checked = NULL );

    /**
     *    Set cache data
     *
     * @param Charcoal_String $key                   string name to identify cached data
     * @param Charcoal_Object $value                 cache data to save
     */
    public function setCache( $key, $value );

    /**
     * Get framework/project/application path
     *
     * @param Charcoal_String|string $virtual_path        virtual path including macro key like '%BASE_DIR%', '%WEBAPP_DIR%', etc.
     * @param Charcoal_String|string $filename            file path
     *
     * @return Charcoal_File         file object
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
    public function getPath( $virtual_path, $filename = NULL );

    /**
     * Get framework/project/application file
     *
     * @param Charcoal_String|string $virtual_path        virtual path including macro key like '%BASE_DIR%', '%WEBAPP_DIR%', etc.
     * @param Charcoal_String|string $filename            file path
     *
     * @return Charcoal_File         file object
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
    public function getFile( $virtual_path, $filename = NULL );

    /**
     * load another module
     *
     * @param Charcoal_ObjectPath|Charcoal_String|string $module_path      module path to load
     */
    public function loadModule( $module_path );

    /**
     *  add an event to task manager
     *
     * @param Charcoal_IEvent $event
     */
    public function pushEvent( $event );

    /**
     *  returns event queue
     *
     * @return Charcoal_IEventQueue
     */
    public function getEventQueue();
}

