<?php
/**
* Cookie Test Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class CookieTestTask extends Charcoal_TestTask
{
	/**
	 * setup test
	 */
	public function setUp()
	{
		// remove all headers
		$headers = headers_list();
		foreach( $headers as $h ){
			header_remove( $h );
		}
	}

	/**
	 * clean up test
	 */
	public function cleanUp()
	{
	}

	/**
	 * get headers
	 */
	private function get_headers()
	{
		return implode( ",", xdebug_get_headers() );
	}

	/**
	 * execute tests
	 */
	public function test( Charcoal_String $action, Charcoal_IEventContext $context )
	{
		$action = us($action);

		// cookie component
		$cookie = Charcoal_DIContainer::getComponent( s('cookie@:charcoal:http') );

		switch( $action ){
		case "test1":
			$cookie->setName( s("foo") );
			$cookie->setValue( s("bar") );
			$cookie->write();
			echo $this->get_headers();
			break;
		case "test2":
			$cookie->setName( s("foo") );
			$cookie->setValue( s("bar") );
			$cookie->setPath( s("baz") );
			$cookie->setDomain( s("qux.com") );
			$cookie->write();
			echo $this->get_headers();
			break;
		}
	}

}

return __FILE__;