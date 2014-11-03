<?php
/**
* Event Context Interface
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IEventContext
{
	/**
	 *	Get current procedure object
     *
     * @return Charcoal_IProcedure
	 */
	public function getProcedure();

	/**
	 *	Set current procedure object
	 *
	 * @param Charcoal_IProcedure $procedure   Procedure object to set
	 */
	public function setProcedure( $procedure );

	/**
	 *	Get current request object
	 *
	 *	@return Charcoal_IRequest
	 */
	public function getRequest();

	/**
	 *	Set current request object
	 *
	 * @param Charcoal_IRequest $request   Request object to set
	 */
	public function setRequest( $request );

	/**
	 *	Get current event object
	 *
	 *	@return Charcoal_IEvent
	 */
	public function getEvent();

	/**
	 *	Set current event object
	 *
	 * @param Charcoal_IEvent $event   Event object to set
	 */
	public function setEvent( $event );

	/**
	 *	Get current sequence object
	 *
	 *	@return Charcoal_IEvent
     *
     * @return Charcoal_ISequence
	 */
	public function getSequence();

	/**
	 *	Set current event object
	 *
	 * @param Charcoal_ISequence $sequence   Ssequence object to set
	 */
	public function setSequence( $sequence );

	/**
	 *	Get current response object
     *
     * @return Charcoal_Response
	 */
	public function getResponse();

	/**
	 *	Set current response object
	 *
	 * @param Charcoal_IResponse $response   Response object to set
	 */
	public function setResponse( $response );

	/**
	 *	Get event queue object
     *
     * @return Charcoal_IEventQueue      event queue object
	 */
	public function getEventQueue();

	/**
	 *	Set event queue object
	 *
	 * @param Charcoal_IEventQueue $event_queue   event queue object
	 */
	public function setEventQueue( $event_queue );

}

