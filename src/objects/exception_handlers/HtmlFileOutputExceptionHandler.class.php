<?php
/**
* HTMLエラーダンプ例外ハンドラ
*
* PHP version 5
*
* @package    objects.exception_handlers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_HtmlFileOutputExceptionHandler extends Charcoal_AbstractExceptionHandler
{
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

		log_info( "system, debug", "exception_handler",  " handled a framework exception!" );

		// Create Debug Trace Renderer
		try{
			$debugtrace_renderer = $this->getSandbox()->createObject( 'html', 'debugtrace_renderer', array(), 'Charcoal_IDebugtraceRenderer' );
			log_info( "system, debug", "exception_handler", "debugtrace_renderer[$debugtrace_renderer] created." );

			// Render exception
			$error_html = $debugtrace_renderer->output( $e );
			log_info( "system, debug", "exception_handler", "debugtrace_renderer[$debugtrace_renderer] output html." );

			// generate error dump(HTML)
			log_error( 'error_dump', "exception_handler", $error_html);
			log_info( "system, debug", "exception_handler", "error_html:\n$error_html" );

			return TRUE;
		}
		catch ( Exception $e )
		{
			_catch( $e );

			log_info( "system, debug", "exception_handler", "debugtrace_renderer[$debugtrace_renderer] creation failed." );
		}

		return FALSE;
	}

}

