<?php
/**
* Context class for event
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_EventContext extends Charcoal_Object implements Charcoal_IEventContext
{
	private $procedure;
	private $request;
	private $event;
	private $sequence;
	private $response;
	private $sandbox;

	/**
	 *	Construct
	 */
	public function __construct( $sandbox )
	{
		parent::__construct();

		Charcoal_ParamTrait::checkSandbox( 1, $sandbox );

		$this->sandbox = $sandbox;
	}

	/**
	 *	Get current procedure object
     *
     * @return Charcoal_IProcedure
	 */
	public function getProcedure()
	{
		return $this->procedure;
	}

	/**
	 *	Set current procedure object
	 *
	 * @param Charcoal_IProcedure $procedure   Procedure object to set
	 */
	public function setProcedure( $procedure )
	{
//		Charcoal_ParamTrait::checkImplements( 1, 'Charcoal_IProcedure', $procedure );

		$this->procedure = $procedure;
	}

	/**
	 *	Get current request object
	 *
	 *	@return Charcoal_IRequest
	 */
	public function getRequest()
	{
		return $this->request;
	}

	/**
	 *	Set current request object
	 *
	 * @param Charcoal_IRequest $request   Request object to set
	 */
	public function setRequest( $request )
	{
//		Charcoal_ParamTrait::checkImplements( 1, 'Charcoal_IRequest', $request );

		$this->request = $request;
	}

	/**
	 *	Get current event object
	 *
	 *	@return Charcoal_IEvent
	 */
	public function getEvent()
	{
		return $this->event;
	}

	/**
	 *	Set current event object
	 *
	 * @param Charcoal_IEvent $event   Event object to set
	 */
	public function setEvent( $event )
	{
//		Charcoal_ParamTrait::checkImplements( 1, 'Charcoal_IEvent', $event );

		$this->event = $event;
	}

	/**
	 *	Get current sequence object
	 *
	 *	@return Charcoal_IEvent
     *
     * @return Charcoal_ISequence
	 */
	public function getSequence()
	{
		return $this->sequence;
	}

	/**
	 *	Set current event object
	 *
	 * @param Charcoal_ISequence $sequence   Ssequence object to set
	 */
	public function setSequence( $sequence )
	{
//		Charcoal_ParamTrait::checkImplements( 1, 'Charcoal_ISequence', $sequence );

		$this->sequence = $sequence;
	}

	/**
	 *	Get current response object
     *
     * @return Charcoal_Response
	 */
	public function getResponse()
	{
		return $this->response;
	}

	/**
	 *	Set current response object
	 *
	 * @param Charcoal_IResponse $response   Response object to set
	 */
	public function setResponse( $response )
	{
//		Charcoal_ParamTrait::checkImplements( 1, 'Charcoal_IResponse', $response );

		$this->response = $response;
	}


	/**
	 *	Create and configure an object 
	 *
	 * @param Charcoal_IResponse $response   Response object to set
	 */
	public function getObject( $obj_path, $type_name, $config = NULL )
	{
//		Charcoal_ParamTrait::checkStringOrObjectPath( 1, $obj_path );
//		Charcoal_ParamTrait::checkString( 2, $type_name );
//		Charcoal_ParamTrait::checkConfig( 3, $config, TRUE );

		try{
			$object = $this->sandbox->createObject( $obj_path, $type_name );

			if ( $config ){
				$object->configure( $config );
			}

			return $object;
		}
		catch( Exception $ex )
		{
			_catch( $ex );
			_throw( new Charcoal_EventContextException( 'getObject', $ex ) );
		}
	}

	/**
	 *	Create and configure a component 
	 *
	 * @param Charcoal_IResponse $response   Response object to set
	 */
	public function getComponent( $obj_path, $config = NULL )
	{
//		Charcoal_ParamTrait::checkStringOrObjectPath( 1, $obj_path );
//		Charcoal_ParamTrait::checkConfig( 2, $config, TRUE );

		try{
			$component = $this->sandbox->getContainer()->getComponent( $obj_path );

			if ( $config ){
				$component->configure( $config );
			}

			return $component;
		}
		catch( Exception $ex )
		{
			_catch( $ex );
			_throw( new Charcoal_EventContextException( 'getComponent', $ex ) );
		}
	}


	/**
	 *	Get cache data
	 *
	 * @param Charcoal_String $key                   string name to identify cached data
	 * @param Charcoal_String $type_name_checked     checks type(class/interface) if not NULL
	 */
	public function getCache( $key, Charcoal_String $type_name_checked = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkString( 2, $type_name_checked, TRUE );

		try{
			$type_name_checked = us($type_name_checked);

			$cached_data = Charcoal_Cache::get( $key );

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
			_throw( new Charcoal_EventContextException( 'getCache', $ex ) );
		}
	}

	/**
	 *	Set cache data
	 *
	 * @param Charcoal_String $key                   string name to identify cached data
	 * @param Charcoal_Object $value                 cache data to save
	 */
	public function setCache( $key, $value )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkObject( 2, $value, TRUE );

		try{
			Charcoal_Cache::set( $key, $value );
		}
		catch( Exception $ex )
		{
			_catch( $ex );
			_throw( new Charcoal_EventContextException( 'setCache', $ex ) );
		}
	}


	/**
	 * Get framework/project/application path
	 *
	 * @param Charcoal_String $virtual_path          virtual path including macro key like '%BASE_DIR%', '%WEBAPP_DIR%', etc.
	 * @param Charcoal_Object $value                 cache data to save
	 *
	 * @return Charcoal_String        full path string
	 *
	 * [macro keyword sample]
	 *
	 *      macro keyword       |                return value(real path)
	 * -------------------------|------------------------------------------------
	 *   %APPLICATION_DIR%      | (web_app_root)/webapp/(project_name)/apps/(application name)/
	 *   %PROJECT_DIR%          | (web_app_root)/webapp/(project_name)/
	 *   %WEBAPP_DIR%           | (web_app_root)/webapp/
	 * 
	 *
	 * @see Charcoal_ResourceLocator
	 *
	 */
	public function getPath( $virtual_path, $filename = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $virtual_path );
//		Charcoal_ParamTrait::checkString( 2, $filename, TRUE );

		if ( $filename )
			return Charcoal_ResourceLocator::getPath( $virtual_path, $filename );
		else
			return Charcoal_ResourceLocator::getPath( $virtual_path );
	}


	/**
	 * Get framework/project/application file
	 *
	 * @param Charcoal_String $virtual_path          virtual path including macro key like '%BASE_DIR%', '%WEBAPP_DIR%', etc.
	 * @param Charcoal_Object $value                 cache data to save
	 *
	 * @return Charcoal_File        file object
	 *
	 * [macro keyword sample]
	 *
	 *      macro keyword       |                return value(real path)
	 * -------------------------|------------------------------------------------
	 *   %APPLICATION_DIR%      | (web_app_root)/webapp/(project_name)/apps/(application name)/
	 *   %PROJECT_DIR%          | (web_app_root)/webapp/(project_name)/
	 *   %WEBAPP_DIR%           | (web_app_root)/webapp/
	 * 
	 *
	 * @see Charcoal_ResourceLocator
	 *
	 */
	public function getFile( $virtual_path, $filename = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $virtual_path );
//		Charcoal_ParamTrait::checkString( 2, $filename, TRUE );

		if ( $filename )
			return Charcoal_ResourceLocator::getFile( $virtual_path, $filename );
		else
			return Charcoal_ResourceLocator::getFile( $virtual_path );
	}
}


