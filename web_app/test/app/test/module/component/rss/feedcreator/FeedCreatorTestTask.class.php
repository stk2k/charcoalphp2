<?php
/**
* Test Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class FeedCreatorTestTask extends Charcoal_TestTask
{
	/**
	 * check if action will be processed
	 */
	public function isValidAction( $action )
	{
		switch( $action ){
		case "generate_feed":
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * setup test
	 */
	public function setUp( $action, $context )
	{

	}

	/**
	 * clean up test
	 */
	public function cleanUp( $action, $context )
	{
	}

	/**
	 * execute tests
	 */
	public function test( $action, $context )
	{
		$request   = $context->getRequest();

		$action = us($action);

		// FeedCreator
		$feedcreator = $context->getComponent( 'feedcreator@:rss:feedcreator' );

		$config = new Charcoal_Config( $this->getSandbox()->getEnvironment() );

		$feedcreator->configure( $config );

		switch( $action ){
		case "generate_feed":
			$feedcreator
				->setTitle('foo')
				->setLink('http://charcoalphp.org')
				->setDescription('Hello')
				->addItem(array('link'=>'http://charcoalphp.org/test'))
				->setTitle('bar')
				->setLink('http://charcoalphp.org/test2')
				->setDescription('Chao!')
				->outputFeed();

			return TRUE;
		}

		return FALSE;
	}

}

return __FILE__;