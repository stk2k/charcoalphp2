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
class DivisionByZeroException extends Exception
{
	public function __construct()
	{
		parent::__construct( 'divided by zero' );
	}
}

class CalcTask extends Charcoal_Task
{
	/**
	 * process event
	 *
	 * @param Charcoal_IEventContext $context   event context
	 */
	public function processEvent( $context )
	{
		$request   = $context->getRequest();
		$response  = $context->getResponse();
		$sequence  = $context->getSequence();
		$procedure = $context->getProcedure();

		// Get parameter from request
		$a = $request->getInteger( 'a', 0 );
		$b = $request->getInteger( 'b', 0 );
		$op = $request->getString( 'op', '+' );

		switch( $op ){
		case '+':
			$result = $a + $b;
			break;
		case '-':
			$result = $a - $b;
			break;
		case '*':
			$result = $a * $b;
			break;
		case '/':
			if ( $b == 0 ){
				throw new DivisionByZeroException();
			}
			$result = $a / $b;
			break;
		}

		// show message
		echo "result:" . $result . eol();

		// return TRUE if processing the procedure success.
		return TRUE;
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
		echo "Exception:" . $e->getMessage() . "<br>";
		throw new Charcoal_HttpException(500);
	}

}

return __FILE__;