<?php
/**
* File Test Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class FileTestTask extends Charcoal_TestTask
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
		return implode( ",", headers_list() );
	}

	/**
	 * execute tests
	 */
	public function test( $action, $context )
	{
		$action = us($action);

		// file system component
		$fs = Charcoal_DIContainer::getComponent( s('file_system@:charcoal:file') );

		switch( $action ){
		case "create_dir":
			$dir = $fs->createDirectory( s("hoge"), s("707") );
			echo "created dir: $dir" . PHP_EOL;
			break;
		case "create_file":
			$file = $fs->createFile( s("test.txt"), s("707"), s("Hello, File System!") );
			echo "created file: $file" . PHP_EOL;
			break;
		}
	}

}

return __FILE__;