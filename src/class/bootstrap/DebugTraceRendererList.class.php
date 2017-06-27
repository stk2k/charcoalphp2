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
    /** @var array */
    private $renderers;
    
    /** @var Charcoal_Sandbox */
    private $sandbox;

    /**
     *  Constructor
     *
     * @param Charcoal_Sandbox $sandbox
     */
    public function __construct( $sandbox )
    {
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
        $this->renderers[] = $renderer;
    }

    /**
     * Render debug trace
     *
     * @param Exception $e
     *
     * @return bool|Charcoal_Boolean
     */
    public function render( $e )
    {
        if ( !$this->renderers ){
            $this->renderers = array();

            if ( !$this->sandbox->isLoaded() ){
                return b(TRUE);
            }

            // Create Debug Trace Renderer
            $debugtrace_renderers = $this->sandbox->getProfile()->getArray( 'DEBUGTRACE_RENDERER' );

            if ( $debugtrace_renderers )
            {
                foreach( $debugtrace_renderers as $renderer_name )
                {
                    if ( strlen($renderer_name) === 0 )    continue;
                    try{
                        $renderer = $this->sandbox->createObject( $renderer_name, 'debugtrace_renderer', array(), array(), 'Charcoal_IDebugtraceRenderer' );
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
