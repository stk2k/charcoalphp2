<?php
/**
* Event Context Interface
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_IEventContext extends Charcoal_ICharcoalObject
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
	public function setProcedure( Charcoal_IProcedure $procedure );

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
	public function setRequest( Charcoal_IRequest $request );

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
	public function setEvent( Charcoal_IEvent $event );

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
	public function setSequence( Charcoal_ISequence $sequence );

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
	public function setResponse( Charcoal_IResponse $response );

}

