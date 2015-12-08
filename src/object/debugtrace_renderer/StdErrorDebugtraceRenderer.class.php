<?php
/**
* Console Debug Trace Renderer
*
* PHP version 5
*
* @package    objects.debugtrace_renderers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_StdErrorDebugtraceRenderer extends Charcoal_ConsoleDebugtraceRenderer
{
    /*
     *    コンストラクタ
     */
    public function __construct()
    {
    }

    /**
     * Initialize instance
     *
     * @param Charcoal_Config $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );

    }

    /**
     * Render debug trace
     *
     */
    public function render( $e )
    {
        Charcoal_ParamTrait::validateException( 1, $e );

        fputs( STDERR, $this->output($e) );

        return TRUE;
    }

}

