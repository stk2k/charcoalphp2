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

class CalkTask extends Charcoal_Task
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

		// Get parameter from request
		$a = $request->getInteger( s('a'), i(0) );
		$b = $request->getInteger( s('b'), i(0) );

		$result = ui($a) + ui($b);

		// show message
		echo "result:" . $result . eol();

		// return TRUE if processing the procedure success.
		return b(TRUE);
	}
}

return __FILE__;