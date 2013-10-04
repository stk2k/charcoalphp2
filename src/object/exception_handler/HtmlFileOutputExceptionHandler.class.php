<?php
/**
* HTMLエラーダンプ例外ハンドラ
*
* PHP version 5
*
* @package    objects.exception_handlers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
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

		log_info( 'system, debug', "handled a framework exception!", 'exception_handler' );

		// Create Debug Trace Renderer
		try{
			$debugtrace_renderer = $this->getSandbox()->createObject( 'html', 'debugtrace_renderer', array(), 'Charcoal_IDebugtraceRenderer' );
			log_info( 'system, debug', "debugtrace_renderer[$debugtrace_renderer] created.", 'exception_handler' );

			// Render exception
			$error_html = $debugtrace_renderer->output( $e );
			log_info( 'system, debug', "debugtrace_renderer[$debugtrace_renderer] output html.", 'exception_handler' );

			// generate error dump(HTML)
			log_error( 'error_dump', $error_html, 'exception_handler');
			log_info( 'system, debug', "error_html:\n$error_html", 'exception_handler' );

			return TRUE;
		}
		catch ( Exception $e )
		{
			_catch( $e );

			log_info( 'system, debug', "debugtrace_renderer[$debugtrace_renderer] creation failed.", 'exception_handler' );
		}

		return FALSE;
	}

}

