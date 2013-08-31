<?php
/**
* HTMLエラーダンプ例外ハンドラ
*
* PHP version 5
*
* @package    events
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_HtmlFileOutputExceptionHandler extends Charcoal_CharcoalObject implements Charcoal_IExceptionHandler
{
	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
	}

	/**
	 * フレームワーク例外ハンドラ
	 */
	public function handleFrameworkException( Charcoal_CharcoalException $e )
	{
		log_info( "system, debug", "exception_handler",  " handled a framework exception!" );

		// Create Debug Trace Renderer
		$debugtrace_renderer = Charcoal_Profile::getString( s('DEBUGTRACE_RENDERER'), s("html") );
		log_info( "system, debug", "exception_handler", "debugtrace_renderer: $debugtrace_renderer" );

		try{
			$debugtrace_renderer = Charcoal_Factory::createObject( s($debugtrace_renderer), s('debugtrace_renderer'), s('Charcoal_IDebugtraceRenderer') );
			log_info( "system, debug", "exception_handler", "debugtrace_renderer[$debugtrace_renderer] created." );

			// Render exception
			$error_html = $debugtrace_renderer->output( $e );
			log_info( "system, debug", "exception_handler", "debugtrace_renderer[$debugtrace_renderer] output html." );

			// generate error dump(HTML)
			log_error( 'error_dump', "exception_handler", $error_html);
			log_info( "system, debug", "exception_handler", "error_html:\n$error_html" );
		}
		catch ( Exception $e )
		{
			_catch( $e );

			log_info( "system, debug", "exception_handler", "debugtrace_renderer[$debugtrace_renderer] creation failed." );
		}
	}

	/**
	 * 例外ハンドラ
	 */
	public function handleException( Exception $e )
	{
		log_info( "system, debug", "exception_handler",  " handled an exception!" );

	}

}

