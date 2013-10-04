<?php
/**
* debugtrace rendrer list
*
* PHP version 5
*
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_DebugTraceRendererList extends Charcoal_Object
{
	private $renderers;
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
	 * add debugtrace renderer
	 * 
	 * @param Charcoal_IDebugtraceRenderer $renderer       renderer to add
	 */
	public function add( $renderer )
	{
//		Charcoal_ParamTrait::checkImplements( 1, 'Charcoal_IDebugtraceRenderer', $renderer );

		$this->renderers[] = $renderer;
	}

	/**
	 * Render debug trace
	 *
	 * @param Charcoal_String $title  title
	 */
	public function render( Exception $e )
	{
//		Charcoal_ParamTrait::checkException( 1, $e );

		if ( !$this->renderers ){
			$this->renderers = array();

			if ( !$this->sandbox->isLoaded() ){
				return;
			}

			// Create Debug Trace Renderer
			$debugtrace_renderers = $this->sandbox->getProfile()->getArray( 'DEBUGTRACE_RENDERER' );

			if ( $debugtrace_renderers )
			{
				foreach( $debugtrace_renderers as $renderer_name )
				{
					if ( strlen($renderer_name) === 0 )    continue;
					try{
						$renderer = $this->sandbox->createObject( $renderer_name, 'debugtrace_renderer', array(), 'Charcoal_IDebugtraceRenderer' );
						$this->renderers[] = $renderer;
					}
					catch ( Exception $e )
					{
						_catch( $e );
						echo( "debugtrace_renderer creation failed:$e" );
					}
				}
			}
		}

		$result = b(FALSE);
		foreach( $this->renderers as $renderer ){
			$ret = $renderer->render( $e );
			if ( $ret === TRUE || $ret instanceof Charcoal_Boolean && $ret->isTrue() ){
				$result = b(TRUE);
			}
		}

		return $result;
	}

}
