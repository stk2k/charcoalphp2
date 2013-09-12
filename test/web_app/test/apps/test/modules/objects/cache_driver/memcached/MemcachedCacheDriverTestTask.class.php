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


class MemcachedCacheDriverTestTask extends Charcoal_TestTask
{
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
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * セットアップ
	 */
	public function setUp( $action )
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

		default:
			break;
		}

	}

	/**
	 * クリーンアップ
	 */
	public function cleanUp( $action )
	{
	}

	/**
	 * テスト
	 */
	public function test( $action, $context )
	{
		$action = us($action);

		$cache_driver = Charcoal_Factory::createObject( s('memcached'), s('cache_driver'), s('Charcoal_ICacheDriver') );
		Charcoal_Cache::register( s('memcached'), $cache_driver );

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
		}

		return FALSE;
	}

}

return __FILE__;