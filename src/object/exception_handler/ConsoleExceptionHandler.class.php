<?php
/**
* コンソール出力用例外ハンドラ
*
* PHP version 5
*
* @package    objects.exception_handlers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ConsoleExceptionHandler extends Charcoal_AbstractExceptionHandler
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
        Charcoal_ParamTrait::validateException( 1, $e );

        log_info( "system, debug", "exception", "handled a framework exception!" );

        try{
            // create debugtrace rederer
            $renderer = $this->getSandbox()->createObject( 'console', 'debugtrace_renderer', array(), 'Charcoal_IDebugtraceRenderer' );

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

