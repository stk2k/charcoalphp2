<?php
/**
* ConfigPropertySet Test Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class ConfigPropertySetTestTask extends Charcoal_TestTask
{
	/**
	 * check if action will be processed
	 */
	public function isValidAction( $action )
	{
		switch( $action ){
		case "get_section":
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * setup test
	 */
	public function setUp( $action )
	{
		$temp_dir = Charcoal_ResourceLocator::getApplicationPath( 'tmp' );

		switch( $action ){
		case "get_section":
$ini_file = <<< INI_FILE
[first_section]
one = 1
five = 5
animal = BIRD

[second_section]
path = "/usr/local/bin"
URL = "http://www.example.com/~username"

[third_section]
phpversion[] = "5.0"
phpversion[] = "5.1"
phpversion[] = "5.2"
phpversion[] = "5.3"
INI_FILE;

			file_put_contents($temp_dir.'/test.ini',$ini_file);

			break;
		}
	}

	/**
	 * clean up test
	 */
	public function cleanUp( $action )
	{
		switch( $action ){
		case "get_section":
			return TRUE;
		}
	}

	/**
	 * execute tests
	 */
	public function test( $action, $context )
	{
		$action = us($action);

		$temp_dir = Charcoal_ResourceLocator::getApplicationPath( s('tmp') );

		switch( $action ){
		case "get_section":

			$ini_data = parse_ini_file($temp_dir.'/test.ini',true);

			$config = new Charcoal_Config( $ini_data );

			$first_section  = $config->getSection( s('first_section') );
			$second_section = $config->getSection( s('second_section') );
			$third_section  = $config->getSection( s('third_section') );

			$this->assertEquals( 1, $first_section['one'] );
			$this->assertEquals( 5, $first_section['five'] );
			$this->assertEquals( 'BIRD', $first_section['animal'] );

			$this->assertEquals( "/usr/local/bin", $second_section['path'] );
			$this->assertEquals( "http://www.example.com/~username", $second_section['URL'] );

			$this->assertEquals( array("5.0","5.1","5.2","5.3"), $third_section['phpversion'] );

			return TRUE;
		}

		return FALSE;
	}

}

return __FILE__;