<?php
/**
* HTTP Error Exception Handler
*
* PHP version 5
*
* @package    events
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_HttpErrorDocumentExceptionHandler extends Charcoal_CharcoalObject implements Charcoal_IExceptionHandler
{
	private $_show_exception_stack;

	/*
	 *	Construct object
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
		$this->_show_exception_stack = $config->getBoolean( s('show_exception_stack'), b(TRUE) )->getValue();
	}

	/**
	 * Handle framework exception
	 */
	public function handleFrameworkException( Charcoal_CharcoalException $e )
	{
		if ( $e instanceof Charcoal_HttpException )
		{
			$status_code = $e->getStatusCode()->getValue();

			// Show HTTP error document
			Charcoal_Framework::showHttpErrorDocument( i($status_code) );

			log_error( 'system,error', 'exception', "http_exception: status_code=$status_code");

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Handle non-framework exception
	 */
	public function handleException( Exception $e )
	{
		return FALSE;
	}

}

