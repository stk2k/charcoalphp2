<?php
/**
* exception handler list
*
* PHP version 5
*
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ExceptionHandlerList extends Charcoal_Object
{
    /** @var  Charcoal_IExceptionHandler[] */
    private $handlers;

    /** @var  Charcoal_Sandbox */
    private $sandbox;

    /**
     *  Constructor
     *
     * @param Charcoal_Sandbox $sandbox
     */
    public function __construct( $sandbox )
    {
//        Charcoal_ParamTrait::validateSandbox( 1, $sandbox );

        $this->sandbox = $sandbox;

        parent::__construct();
    }

    /**
     * add exception handler
     *
     * @param Charcoal_IExceptionHandler $handler       renderer to add
     */
    public function add( $handler )
    {
//        Charcoal_ParamTrait::validateImplements( 1, 'Charcoal_IExceptionHandler', $handler );

        $this->handlers[] = $handler;
    }

    /**
     * initialize exception handler list
     */
    private function init()
    {
        if ( !$this->handlers ){
            $this->handlers = array();

            if ( !$this->sandbox->isLoaded() ){
                return;
            }

            $exception_handlers = $this->sandbox->getProfile()->getArray( 'EXCEPTION_HANDLERS' );
            if ( $exception_handlers ){
                foreach( $exception_handlers as $handler_name ){
                    if ( strlen($handler_name) === 0 )    continue;
                    $handler = $this->sandbox->createObject( $handler_name, 'exception_handler', array(), array(), 'Charcoal_IExceptionHandler' );
                    $this->handlers[] = $handler;
                }
            }
        }
    }

    /**
     * execute exception handlers
     *
     * @param Exception $e     exception to handle
     *
     * @return boolean        TRUE means the exception is handled, otherwise FALSE
     */
    public function handleException( $e )
    {
        try{
            $this->init();
        }
        catch(Exception $e){
            _throw( new Charcoal_ExceptionHandlerListInitException('failed to init exception handler', $e) );
        }

        foreach( $this->handlers as $handler ){
            log_info( "system, debug", "calling exception handler[$handler].", "exception" );
            $ret = $handler->handleException( $e );
            if ( b($ret)->isTrue() ){
                return true;
            }
        }

        return false;
    }
}
