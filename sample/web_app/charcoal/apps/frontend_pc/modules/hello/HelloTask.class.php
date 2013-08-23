<?php
/**
* Hello Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class HelloTask extends Charcoal_Task
{
	/**
	 * process event
	 *
	 * @param Charcoal_IEventContext $context   event context
	 */
	public function processEvent( Charcoal_IEventContext $context )
	{
		$request   = $context->getRequest();
		$response  = $context->getResponse();
		$sequence  = $context->getSequence();
		$procedure = $context->getProcedure();

		// show message
		echo "Hello, World!" . eol();

		// return TRUE if processing the procedure success.
		return b(TRUE);
	}
}

return __FILE__;