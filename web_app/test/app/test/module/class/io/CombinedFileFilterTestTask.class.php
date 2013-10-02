<?php
/**
* CombinedFileFilter Test Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/
class CombinedFileFilterTestTask extends Charcoal_TestTask
{
	/**
	 * check if action will be processed
	 */
	public function isValidAction( $action )
	{
		switch( $action ){
		case "combined_regex":
		case "combined_wildcard":
		case "combined_complexed":

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
		case "combined_regex":

			$filter1 = new Charcoal_RegExFileFilter( s('/sample_file1\.txt/') );
			$filter2 = new Charcoal_RegExFileFilter( s('/sample_file[23]\.txt/') );

			$filter = new Charcoal_CombinedFileFilter( v(array($filter1,$filter2)) );

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

		case "combined_wildcard":

			return TRUE;


		case "combined_complexed":

			return TRUE;
		}

		return FALSE;
	}

}

return __FILE__;