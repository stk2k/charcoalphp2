<?php
/**
* RegExFileFilter Test Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/
class RegExFileFilterTestTask extends Charcoal_TestTask
{
	/**
	 * check if action will be processed
	 */
	public function isValidAction( $action )
	{
		switch( $action ){
		case "no_regex":
		case "simple_regex":
		case "complexed_regex":

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
		$action = us($action);

		$test_data_dir = $context->getFile( s('%APPLICATION_DIR%/test_data/class/io') );

		switch( $action ){
		case "no_regex":

			$filter = new Charcoal_RegExFileFilter( s('/sample_file1\.txt/') );

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

		case "simple_regex":

			$filter = new Charcoal_RegExFileFilter( s('/sample_file[123]\.txt/') );

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

			return TRUE;

		case "complexed_regex":

			return TRUE;
		}

		return FALSE;
	}

}

return __FILE__;