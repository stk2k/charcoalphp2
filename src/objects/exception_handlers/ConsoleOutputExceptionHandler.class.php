<?php
/**
* コンソール出力用例外ハンドラ
*
* PHP version 5
*
* @package    events
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ConsoleOutputExceptionHandler extends Charcoal_AbstractExceptionHandler
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

		log_info( "system, debug", "exception", "handled a framework exception!" );

		// Create Debug Trace Renderer
		$renderer = $this->getSandbox()->getProfile()->getString( 'DEBUGTRACE_RENDERER', 'html' );
		log_info( "system, debug", "exception", "renderer: $renderer" );

		try{
			$renderer = $this->getSandbox()->createObject( $renderer, 'debugtrace_renderer', array(), 'Charcoal_IDebugtraceRenderer' );

			// Render exception
			$renderer->render( $e );

			return TRUE;
		}
		catch ( Exception $e )
		{
			_catch( $e );

			log_info( "system, debug", "exception", "debugtrace_renderer[$renderer] creation failed." );
		}

		return FALSE;
	}

}

