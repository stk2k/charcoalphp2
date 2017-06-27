<?php
/**
* Intarface of basic procedure
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

interface Charcoal_IProcedure extends Charcoal_ICharcoalObject
{

    /**
     * Checks if the procedure has forward target procedure
     *
     * @return boolean      TRUE if the procedure has forward target, FALSE otherwise
     */
    public function hasForwardTarget();

    /**
     * Retrieve object path of the procedure which is specified as a forward target
     *
     * @return string      object path of forward target procedure
     */
    public function getForwardTarget();

    /**
     * Execute procedure
     *
     * @param Charcoal_IRequest $request      request object
     * @param Charcoal_IResponse $response    response object
     * @param Charcoal_Session $session       session object
     */
    public function execute( $request, $response, $session = NULL );

    /*
     *    returns TRUE if this procedure is debug mode
     */
    public function isDebugMode();

    /*
     *    returns TRUE if logger is enabled
     */
    public function isLoggerEnabled();

    /*
     *    returns log level
     */
    public function getLogLevel();

    /*
     *    returns loggers
     */
    public function getLoggers();

}

