<?php
/**
* Command Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class CommandTask extends CommandTaskBase
{
	/**
	 * setup
	 *
	 * @return void
	 */
	public function setUp()
	{
	}

	/**
	 * clean up
	 *
	 * @return void
	 */
	public function cleanUp()
	{
	}

	/**
	 * execute command
	 *
	 * @param Charcoal_String $action   action name to execute
	 */
	public function execute( Charcoal_String $action )
	{
		$action = us($action);

		switch( $action ){
		case 'version':
			$version = Charcoal_Framework::getVersion();
			echo "CharcoalPHP {$version}." . PHP_EOL;
			echo "Copyright (c)2008-2013 CharcoalPHP team." . PHP_EOL;

			return TRUE;

		case 'hello':
			echo "Hello, world!" . PHP_EOL;

			return TRUE;
		}

		return FALSE;
	}

}

return __FILE__;