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

class SimpleHtmlDomTestTask extends Charcoal_TestTask
{
	/**
	 * check if action will be processed
	 */
	public function isValidAction( $action )
	{
		switch( $action ){
		case "find":
		case "find2":
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

		// SimpleHtmlDom
		$simplehtmldom = $context->getComponent( 'simplehtmldom@:html:parser:simplehtmldom' );

		$config = new Charcoal_Config( $this->getSandbox()->getEnvironment() );

		$simplehtmldom->configure( $config );

		switch( $action ){
		case "find":
			$simplehtmldom->createFromURL( 'http://charcoalphp.org/' );

			foreach($simplehtmldom->find( 'title' ) as $e){
				echo $e->getInnerText() . PHP_EOL;
			}

			return TRUE;

		case "find2":
			$simplehtmldom->createFromURL( 'http://madousho.blog.fc2.com/' );

			foreach($simplehtmldom->find( 'div[class=mainEntryBody]' ) as $e){
				echo $e->getInnerText() . PHP_EOL;
			}

			return TRUE;
		}

		return FALSE;
	}

}

return __FILE__;