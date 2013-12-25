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

function show_with_children( $node, $level = 0 )
{
	echo str_repeat( '  ', $level ) . $node->getName() . PHP_EOL;
	if ( $node->hasChildren() ){
		foreach( $node->getChildren() as $child ){
			show_with_children( $child, $level + 1 );
		}
	}
}

class TidyTestTask extends Charcoal_TestTask
{
	/**
	 * check if action will be processed
	 */
	public function isValidAction( $action )
	{
		switch( $action ){
		case "parse_string":
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

		// Tidy
		$tidy = $context->getComponent( 'tidy@:html:repair:tidy' );

		$config = new Charcoal_Config();

		$config->set( 'encoding', 'utf8' );

		$tidy->configure( $config );

		switch( $action ){
		case "parse_string":

$html1 = <<< HTMLDATA1
<html>
  <head>
    <title>My Title</name>
  </head>
<BODY>

  <h1>Test Header</hh1>
    <form>Google</textarea>
    <textarea>http://google.com/</textarea></form>
  </company>
HTMLDATA1;

			$tidy->parseString( $html1 );

			show_with_children( $tidy->root() );

			return TRUE;

		}

		return FALSE;
	}

}

return __FILE__;