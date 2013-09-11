<?php
/**
* display version command
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class VersionTask extends Charcoal_Task
{
	/**
	 * Process events
	 *
	 * @param Charcoal_IEventContext $context   event context
	 */
	public function processEvent( Charcoal_IEventContext $context )
	{
		$version = Charcoal_Framework::getVersion();

		echo "CharcoalPHP {$version}." . PHP_EOL;
		echo "Copyright (c)2008-2013 CharcoalPHP team." . PHP_EOL;

		return b(true);
	}
}
