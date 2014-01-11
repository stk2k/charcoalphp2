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

class FileSystemTestTask extends Charcoal_TestTask
{
	/**
	 * check if action will be processed
	 */
	public function isValidAction( $action )
	{
		switch( $action ){
		case "create_dir":
		case "create_file":
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * setup test
	 */
	public function setUp( $action, $context )
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
	public function cleanUp( $action, $context )
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
		$fs = $context->getComponent( 'file_system@:charcoal:file' );

		switch( $action ){
		case "create_dir":
			$dir = $fs->createDirectory( "hoge", "707" );
			echo "created dir: $dir" . PHP_EOL;

			return TRUE;

		case "create_file":
			$file = $fs->createFile( "test.txt", "707", "Hello, File System!" );
			echo "created file: $file" . PHP_EOL;

			return TRUE;

		}
	}

}

return __FILE__;