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

class Charcoal_ConsoleOutputExceptionHandler extends Charcoal_CharcoalObject implements Charcoal_IExceptionHandler
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
		log_info( "system, debug", "exception", "handled a framework exception!" );

		// Create Debug Trace Renderer
		$renderer = Charcoal_Profile::getString( s('DEBUGTRACE_RENDERER'), s("html") );
		log_info( "system, debug", "exception", "renderer: $renderer" );

		try{
			$renderer = Charcoal_Factory::createObject( s($renderer), s('debugtrace_renderer'), s('Charcoal_IDebugtraceRenderer') );

			// Render exception
			$renderer->render( $e );
		}
		catch ( Exception $e )
		{
			_catch( $e );

			log_info( "system, debug", "exception", "debugtrace_renderer[$renderer] creation failed." );
		}

		return TRUE;
	}

	/**
	 * 例外ハンドラ
	 */
	public function handleException( Exception $e )
	{
		log_info( "system, debug", "exception", " handled an exception!" );

	}

}

