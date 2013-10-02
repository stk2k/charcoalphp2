<?php
/**
* exception handler list
*
* PHP version 5
*
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ExceptionHandlerList extends Charcoal_Object
{
	private $handlers;
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

	/**
	 * add exception handler
	 * 
	 * @param Charcoal_IExceptionHandler $handler       renderer to add
	 */
	public function add( $handler )
	{
//		Charcoal_ParamTrait::checkImplements( 1, 'Charcoal_IExceptionHandler', $handler );

		$this->handlers[] = $handler;
	}

	/**
	 * initialize exception handler list
	 */
	private function init()
	{
		if ( !$this->handlers ){
			$this->handlers = array();

			if ( !$this->sandbox->isLoaded() ){
				return;
			}

			$exception_handlers = $this->sandbox->getProfile()->getArray( 'EXCEPTION_HANDLERS' );
			if ( $exception_handlers ){
				foreach( $exception_handlers as $handler_name ){
					if ( strlen($handler_name) === 0 )    continue;
					$handler = $this->sandbox->createObject( $handler_name, 'exception_handler', array(), 'Charcoal_IExceptionHandler' );
					$this->handlers[] = $handler;
				}
			}
		}
	}

	/**
	 * execute exception handlers
	 * 
	 * @param Exception $e     exception to handle
	 * 
	 * @return boolean        TRUE means the exception is handled, otherwise FALSE
	 */
	public function handleException( $e )
	{
//		Charcoal_ParamTrait::checkException( 1, $e );

		$this->init();

		$handled = b(FALSE);
		foreach( $this->handlers as $handler ){
			log_info( "system,debug,error", "calling exception handler[$handler].", "exception" );
			$ret = $handler->handleException( $e );
			$handled->operateOr( $ret );
		}

		return $handled;
	}
}