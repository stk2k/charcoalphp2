<?php
/**
* タスク
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/


class FileCacheDriverTestTask extends Charcoal_TestTask
{
	var	$cache_root;

	/**
	 * check if action will be processed
	 */
	public function isValidAction( $action )
	{
		switch( $action ){
		case "get_empty_data":
		case "get_integer_data":
		case "get_string_data":
		case "get_array_data":
		case "get_boolean_data":
		case "get_float_data":
		case "get_object_data":
		case "set_duration":
		case "delete":
		case "delete_wildcard":
		case "delete_regex":
		case "touch":
		case "touch_wildcard":
		case "touch_regex":
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * セットアップ
	 */
	public function setUp( $action, $context )
	{
		$action = us($action);

		switch( $action ){

		case "get_empty_data":
		case "get_integer_data":
		case "get_string_data":
		case "get_array_data":
		case "get_boolean_data":
		case "get_float_data":
		case "get_object_data":
		case "set_duration":
		case "delete":
		case "delete_wildcard":
		case "delete_regex":
		case "touch":
		case "touch_wildcard":
		case "touch_regex":
			{
				$cache_driver = Charcoal_Factory::createObject( s('file'), s('cache_driver'), s('Charcoal_ICacheDriver') );

				$this->cache_root = Charcoal_ResourceLocator::getPath( s('%APPLICATION_DIR%/cache/') );

				$config['cache_root'] = $this->cache_root;

				$cache_driver->configure( new Charcoal_Config($config) );

				Charcoal_Cache::register( s('file'), $cache_driver );

				$cache_files = glob( $this->cache_root . '/*' );
				if ( $cache_files && is_array($cache_files) ){
					foreach( $cache_files as $cache ){
						$file = new Charcoal_File( s($cache) );
						$name = $file->getName();
						$ext = $file->getExtension();
						if ( $file->exists() && $file->isFile() && in_array($ext,array("meta","data")) ) {
							$file->delete();
							echo "removed cache file: [$cache]" . eol();
						}
					}
				}
			}
			break;
		default:
			break;
		}

	}

	/**
	 * クリーンアップ
	 */
	public function cleanUp( $action, $context )
	{
		$action = us($action);

		switch( $action ){

		case "get_empty_data":
		case "get_integer_data":
		case "get_string_data":
		case "get_array_data":
		case "get_boolean_data":
		case "get_float_data":
		case "get_object_data":
		case "set_duration":
		case "delete":
		case "delete_wildcard":
		case "delete_regex":
		case "touch":
		case "touch_wildcard":
		case "touch_regex":
			{
				$cache_files = glob( $this->cache_root . '/*' );
				if ( $cache_files && is_array($cache_files) ){
					foreach( $cache_files as $cache ){
						$file = new Charcoal_File( s($cache) );
						$name = $file->getName();
						$ext = $file->getExtension();
						if ( $file->exists() && $file->isFile() && in_array($ext,array("meta","data")) ) {
							$file->delete();
							echo "removed cache file: [$cache]" . eol();
						}
					}
				}
			}
			break;
		default:
			break;
		}
	}

	/**
	 * テスト
	 */
	public function test( $action, $context )
	{
		$action = us($action);

		switch( $action ){

		case "get_empty_data":

			Charcoal_Cache::delete( s('foo') );

			$value = Charcoal_Cache::get( s('foo') );

			$this->assertEquals( FALSE, $value );

			return TRUE;

		case "get_integer_data":

			Charcoal_Cache::set( s('foo'), i(100) );
			$value = Charcoal_Cache::get( s('foo') );

			$this->assertEquals( i(100), $value );

			return TRUE;

		case "get_string_data":

			Charcoal_Cache::set( s('foo'), s('bar') );
			$value = Charcoal_Cache::get( s('foo') );

			$this->assertEquals( s('bar'), $value );

			return TRUE;

		case "get_array_data":

			$data = array( 'foo' => 100, 'bar' => 'baz' );

			Charcoal_Cache::set( s('foo'), v($data) );
			$value = Charcoal_Cache::get( s('foo') );

			$this->assertEquals( v($data), $value );

			return TRUE;

		case "get_boolean_data":

			Charcoal_Cache::set( s('foo'), b(TRUE) );
			$value = Charcoal_Cache::get( s('foo') );

			$this->assertEquals( b(TRUE), $value );

			Charcoal_Cache::set( s('foo'), b(FALSE) );
			$value = Charcoal_Cache::get( s('foo') );

			$this->assertEquals( b(FALSE), $value );

			return TRUE;

		case "get_float_data":

			Charcoal_Cache::set( s('foo'), f(3.14) );
			$value = Charcoal_Cache::get( s('foo') );

			$this->assertEquals( f(3.14), $value );

			return TRUE;

		case "get_object_data":

			$data = new Charcoal_DTO();

			$data['foo'] = 100;
			$data['bar'] = 'test';

			Charcoal_Cache::set( s('foo'), $data );
			$value = Charcoal_Cache::get( s('foo') );

			echo "value:" . print_r($value,true) . eol();

			$this->assertEquals( $data, $value );

			return TRUE;

		case "set_duration":

			Charcoal_Cache::set( s('foo'), f(3.14), i(5) );

			$cache_files = glob( $this->cache_root . '/*' );

			$this->assertEquals( FALSE, is_null($cache_files) );
			$this->assertEquals( TRUE, is_array($cache_files) );
			$this->assertEquals( 2, count($cache_files) );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/foo.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/foo.data') );
			$this->assertEquals( f(3.14), Charcoal_Cache::get( s('foo') ) );

			echo "waiting 3 seconds..." . eol();
			sleep(3);

			$this->assertEquals( f(3.14), Charcoal_Cache::get( s('foo') ) );

			echo "waiting 3 seconds..." . eol();
			sleep(3);

			$this->assertEquals( NULL, Charcoal_Cache::get( s('foo') ) );

			return TRUE;

		case "delete":

			Charcoal_Cache::set( s('foo'), f(3.14) );
			Charcoal_Cache::set( s('bar'), i(2) );
			Charcoal_Cache::set( s('baz'), s('hello!') );

			$cache_files = glob( $this->cache_root . '/*' );

			$this->assertEquals( FALSE, is_null($cache_files) );
			$this->assertEquals( TRUE, is_array($cache_files) );
			$this->assertEquals( 6, count($cache_files) );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/foo.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/foo.data') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/bar.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/bar.data') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/baz.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/baz.data') );

			Charcoal_Cache::delete( s('bar') );

			$cache_files = glob( $this->cache_root . '/*' );

			$this->assertEquals( FALSE, is_null($cache_files) );
			$this->assertEquals( TRUE, is_array($cache_files) );
			$this->assertEquals( 4, count($cache_files) );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/foo.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/foo.data') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/bar.meta') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/bar.data') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/baz.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/baz.data') );

			Charcoal_Cache::delete( s('baz') );

			$cache_files = glob( $this->cache_root . '/*' );

			$this->assertEquals( FALSE, is_null($cache_files) );
			$this->assertEquals( TRUE, is_array($cache_files) );
			$this->assertEquals( 2, count($cache_files) );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/foo.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/foo.data') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/bar.meta') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/bar.data') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/baz.meta') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/baz.data') );

			return TRUE;

		case "delete_wildcard":

			Charcoal_Cache::set( s('test.foo'), f(3.14) );
			Charcoal_Cache::set( s('test.bar'), i(2) );
			Charcoal_Cache::set( s('test.baz'), s('hello!') );
			Charcoal_Cache::set( s('no_delete'), s(':)') );

			$cache_files = glob( $this->cache_root . '/*' );

			$this->assertEquals( FALSE, is_null($cache_files) );
			$this->assertEquals( TRUE, is_array($cache_files) );
			$this->assertEquals( 8, count($cache_files) );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/test.foo.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/test.foo.data') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/test.bar.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/test.bar.data') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/test.baz.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/test.baz.data') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/no_delete.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/no_delete.data') );

			Charcoal_Cache::delete( s('test.b*') );

			$cache_files = glob( $this->cache_root . '/*' );

			$this->assertEquals( FALSE, is_null($cache_files) );
			$this->assertEquals( TRUE, is_array($cache_files) );
			$this->assertEquals( 4, count($cache_files) );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/test.foo.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/test.foo.data') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.bar.meta') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.bar.data') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.baz.meta') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.baz.data') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/no_delete.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/no_delete.data') );

			Charcoal_Cache::set( s('test.foo'), f(3.14) );
			Charcoal_Cache::set( s('test.bar'), i(2) );
			Charcoal_Cache::set( s('test.baz'), s('hello!') );
			Charcoal_Cache::set( s('no_delete'), s(':)') );

			Charcoal_Cache::delete( s('test.*') );

			$cache_files = glob( $this->cache_root . '/*' );

			$this->assertEquals( FALSE, is_null($cache_files) );
			$this->assertEquals( TRUE, is_array($cache_files) );
			$this->assertEquals( 2, count($cache_files) );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.foo.meta') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.foo.data') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.bar.meta') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.bar.data') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.baz.meta') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.baz.data') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/no_delete.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/no_delete.data') );
			return TRUE;

		case "delete_regex":

			Charcoal_Cache::set( s('test.foo'), f(3.14) );
			Charcoal_Cache::set( s('test.bar'), i(2) );
			Charcoal_Cache::set( s('test.baz'), s('hello!') );
			Charcoal_Cache::set( s('no_delete'), s(':)') );

			$cache_files = glob( $this->cache_root . '/*' );

			$this->assertEquals( FALSE, is_null($cache_files) );
			$this->assertEquals( TRUE, is_array($cache_files) );
			$this->assertEquals( 8, count($cache_files) );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/test.foo.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/test.foo.data') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/test.bar.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/test.bar.data') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/test.baz.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/test.baz.data') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/no_delete.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/no_delete.data') );

			Charcoal_Cache::deleteRegEx( s('/test\\.ba./'), b(TRUE) );

			$cache_files = glob( $this->cache_root . '/*' );

			$this->assertEquals( FALSE, is_null($cache_files) );
			$this->assertEquals( TRUE, is_array($cache_files) );
			$this->assertEquals( 4, count($cache_files) );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/test.foo.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/test.foo.data') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.bar.meta') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.bar.data') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.baz.meta') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.baz.data') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/no_delete.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/no_delete.data') );

			Charcoal_Cache::set( s('test.foo'), f(3.14) );
			Charcoal_Cache::set( s('test.bar'), i(2) );
			Charcoal_Cache::set( s('test.baz'), s('hello!') );
			Charcoal_Cache::set( s('no_delete'), s(':)') );

			Charcoal_Cache::deleteRegEx( s('/test\\.[foo|bar|baz]/'), b(TRUE) );

			$cache_files = glob( $this->cache_root . '/*' );
echo "files found:" . print_r($cache_files,true) . eol();

			$this->assertEquals( FALSE, is_null($cache_files) );
			$this->assertEquals( TRUE, is_array($cache_files) );
			$this->assertEquals( 2, count($cache_files) );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.foo.meta') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.foo.data') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.bar.meta') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.bar.data') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.baz.meta') );
			$this->assertEquals( FALSE, file_exists($this->cache_root . '/test.baz.data') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/no_delete.meta') );
			$this->assertEquals( TRUE, file_exists($this->cache_root . '/no_delete.data') );
			return TRUE;

			return TRUE;

		case "touch":

			return TRUE;

		case "touch_wildcard":

			return TRUE;

		case "touch_regex":

			return TRUE;
		}

		return FALSE;
	}

}

return __FILE__;