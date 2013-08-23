<?php
/**
* Form Test Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class TransformerTestTask extends Charcoal_TestTask
{
	/**
	 * setup test
	 */
	public function setUp( Charcoal_String $action )
	{

	}

	/**
	 * clean up test
	 */
	public function cleanUp( Charcoal_String $action )
	{
	}

	/**
	 * execute tests
	 */
	public function test( Charcoal_String $action, Charcoal_IEventContext $context )
	{
		$action = us($action);

		switch( $action ){
		case "simple_transform":
			// create simple transformer
			$tr = Charcoal_Factory::createObject( s('simple'), s('transformer') );

			$a = new DTO( v(array("foo"=>"bar")) );
			$b = new DTO();

			$tr->transform( $a, $b );

			$this->assertEquals( "bar", $a->foo );
			$this->assertEquals( "bar", $b->foo );

			break;
		}
	}

}

return __FILE__;