<?php
/**
* HTTP Error Exception Handler
*
* PHP version 5
*
* @package    objects.exception_handlers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_HttpErrorDocumentExceptionHandler extends Charcoal_AbstractExceptionHandler
{
	private $_show_exception_stack;

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$this->_show_exception_stack = $config->getBoolean( 'show_exception_stack', TRUE );
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
		Charcoal_ParamTrait::checkException( 1, $e );

		if ( $e instanceof Charcoal_HttpStatusException )
		{
			$status_code = $e->getStatusCode();

			// Show HTTP error document
			Charcoal_Framework::showHttpErrorDocument( $status_code );

			log_error( 'system,error', 'exception', "http_exception: status_code=$status_code");

			return TRUE;
		}

		return FALSE;
	}

}

