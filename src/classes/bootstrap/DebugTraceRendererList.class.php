<?php
/**
* debugtrace rendrer list
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_DebugTraceRendererList
{
	static private $renderers;

	/**
	 * add debugtrace renderer
	 * 
	 * @param Charcoal_IDebugtraceRenderer $renderer       renderer to add
	 */
	public static function add( Charcoal_IDebugtraceRenderer $renderer )
	{
		self::$renderers[] = $renderer;
	}

	/**
	 * Render debug trace
	 *
	 * @param Charcoal_String $title  title
	 */
	public static function render( Exception $e )
	{
		if ( !self::$renderers ){
			// Create Debug Trace Renderer
			$debugtrace_renderers = Charcoal_Profile::getArray( s('DEBUGTRACE_RENDERER') );

			if ( $debugtrace_renderers )
			{
				foreach( $debugtrace_renderers as $renderer_name )
				{
					try{
						$renderer = Charcoal_Factory::createObject( s($renderer_name), s('debugtrace_renderer'), v(array()), s('Charcoal_IDebugtraceRenderer') );
						self::$renderers[] = $renderer;
					}
					catch ( Exception $e )
					{
						_catch( $e );
						echo( "debugtrace_renderer creation failed:$e" );
					}
				}
			}
			else{
				self::$renderers = array();
			}
		}

		$result = b(FALSE);
		foreach( self::$renderers as $renderer ){
			$ret = $renderer->render( $e );
			if ( $ret && $ret instanceof Charcoal_Boolean && $ret->isTrue() ){
				$result = b(TRUE);
			}
		}

		return $result;
	}

}
