<?php
/**
* WildcardFileFilter Test Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/
class WildcardFileFilterTestTask extends Charcoal_TestTask
{
	/**
	 * check if action will be processed
	 */
	public function isValidAction( $action )
	{
		switch( $action ){
		case "no_wildcard":
		case "question_wildcard":
		case "asterisk_wildcard":

			return TRUE;
		}
		return FALSE;
	}

	/**
	 * setup test
	 */
	public function setUp( $action )
	{
	}

	/**
	 * clean up test
	 */
	public function cleanUp( $action )
	{
	}

	/**
	 * execute tests
	 */
	public function test( $action, $context )
	{
		$action = us($action);

		$test_data_dir = $context->getFile( s('%APPLICATION_DIR%/test_data/classes/io') );

		switch( $action ){
		case "no_wildcard":

			$filter = new Charcoal_WildcardFileFilter( s('sample_file1.txt') );

			$files = $test_data_dir->listFiles( $filter );

			$files_found = array();
			foreach( $files as $file ){
				$files_found[] = $file->getName();
			}

			$expected = array( 'sample_file1.txt' );

			$this->assertEquals( 'array', gettype($files) );
			$this->assertEquals( 1, count($files) );
			$this->assertEquals( array(), array_diff($files_found, $expected) );

			return TRUE;

		case "question_wildcard":

			$filter = new Charcoal_WildcardFileFilter( s('sample_file?.txt') );

			$files = $test_data_dir->listFiles( $filter );

			$files_found = array();
			foreach( $files as $file ){
				$files_found[] = $file->getName();
			}

			$expected = array( 'sample_file1.txt', 'sample_file2.txt', 'sample_file3.txt' );

			$this->assertEquals( 'array', gettype($files) );
			$this->assertEquals( 3, count($files) );
			$this->assertEquals( array(), array_diff($files_found, $expected) );

			return TRUE;


		case "asterisk_wildcard":

			$filter = new Charcoal_WildcardFileFilter( s('s*mple_file1.*') );

			$files = $test_data_dir->listFiles( $filter );

			$files_found = array();
			foreach( $files as $file ){
				$files_found[] = $file->getName();
			}

			$expected = array( 'sample_file1.txt', 'sample_file1.doc', 'simple_file1.txt' );

			$this->assertEquals( 'array', gettype($files) );
			$this->assertEquals( 3, count($files) );
			$this->assertEquals( array(), array_diff($files_found, $expected) );

			return TRUE;
		}

		return FALSE;
	}

}

return __FILE__;