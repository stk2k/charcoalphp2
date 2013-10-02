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
	public function processEvent( $context )
	{
		// show message
		echo "Hello, World!<br />";

		// return TRUE if processing the procedure success.
		return TRUE;
	}
}
