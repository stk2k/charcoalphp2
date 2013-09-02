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

class CalcTask extends Charcoal_Task
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
		$op = $request->getString( s('op'), s('+') );

		switch( $op ){
		case '+':
			$result = ui($a) + ui($b);
			break;
		case '-':
			$result = ui($a) - ui($b);
			break;
		case '*':
			$result = ui($a) * ui($b);
			break;
		case '/':
			$result = ui($a) / ui($b);
			break;
		}

		// show message
		echo "result:" . $result . eol();

		// return TRUE if processing the procedure success.
		return b(TRUE);
	}
}

return __FILE__;