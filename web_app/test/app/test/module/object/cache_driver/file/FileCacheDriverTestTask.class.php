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
    private    $cache_root;

    private $cache_driver;

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
                $cache_driver = $context->createObject( 'file', 'cache_driver', 'Charcoal_ICacheDriver' );

                $this->cache_root = Charcoal_ResourceLocator::getPath( '%CHARCOAL_HOME%/tmp/test/cache/' );

                $config['cache_root'] = $this->cache_root;

                $config = new Charcoal_Config( $this->getSandbox()->getEnvironment(), $config );

                $cache_driver->configure( $config );

                $this->cache_driver = $cache_driver;

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

            $this->cache_driver->delete( 'foo' );

            $value = $this->cache_driver->get( 'foo' );

            $this->assertEquals( FALSE, $value );

            return TRUE;

        case "get_integer_data":

            $this->cache_driver->set( 'foo', 100 );
            $value = $this->cache_driver->get( 'foo' );

            $this->assertEquals( 100, $value );

            return TRUE;

        case "get_string_data":

            $this->cache_driver->set( 'foo', 'bar' );
            $value = $this->cache_driver->get( 'foo' );

            $this->assertEquals( 'bar', $value );

            return TRUE;

        case "get_array_data":

            $data = array( 'foo' => 100, 'bar' => 'baz' );

            $this->cache_driver->set( 'foo', $data );
            $value = $this->cache_driver->get( 'foo' );

            $this->assertEquals( $data, $value );

            return TRUE;

        case "get_boolean_data":

            $this->cache_driver->set( 'foo', TRUE );
            $value = $this->cache_driver->get( 'foo' );

            $this->assertEquals( TRUE, $value );

            $this->cache_driver->set( 'foo', FALSE );
            $value = $this->cache_driver->get( s('foo') );

            $this->assertEquals( FALSE, $value );

            return TRUE;

        case "get_float_data":

            $this->cache_driver->set( 'foo', 3.14 );
            $value = $this->cache_driver->get( 'foo' );

            $this->assertEquals( 3.14, $value );

            return TRUE;

        case "get_object_data":

            $data = new Charcoal_DTO();

            $data['foo'] = 100;
            $data['bar'] = 'test';

            $this->cache_driver->set( 'foo', $data );
            $value = $this->cache_driver->get( 'foo' );

            echo "value:" . print_r($value,true) . eol();

            $this->assertEquals( $data, $value );

            return TRUE;

        case "set_duration":

            $this->cache_driver->set( 'foo', 3.14, 5 );

            $cache_files = glob( $this->cache_root . '/*' );

            $this->assertEquals( FALSE, is_null($cache_files) );
            $this->assertEquals( TRUE, is_array($cache_files) );
            $this->assertEquals( 2, count($cache_files) );
            $this->assertEquals( TRUE, file_exists($this->cache_root . '/foo.meta') );
            $this->assertEquals( TRUE, file_exists($this->cache_root . '/foo.data') );
            $this->assertEquals( 3.14, $this->cache_driver->get( 'foo' ) );

            echo "waiting 3 seconds..." . eol();
            sleep(3);

            $this->assertEquals( 3.14, $this->cache_driver->get( 'foo' ) );

            echo "waiting 3 seconds..." . eol();
            sleep(3);

            $this->assertEquals( NULL, $this->cache_driver->get( 'foo' ) );

            return TRUE;

        case "delete":

            $this->cache_driver->set( 'foo', 3.14 );
            $this->cache_driver->set( 'bar', 2 );
            $this->cache_driver->set( 'baz', 'hello!' );

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

            $this->cache_driver->delete( 'bar' );

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

            $this->cache_driver->delete( 'baz' );

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

            $this->cache_driver->set( 'test.foo', 3.14 );
            $this->cache_driver->set( 'test.bar', 2 );
            $this->cache_driver->set( 'test.baz', 'hello!' );
            $this->cache_driver->set( 'no_delete', ':)' );

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

            $this->cache_driver->delete( 'test.b*' );

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

            $this->cache_driver->set( 'test.foo', 3.14 );
            $this->cache_driver->set( 'test.bar', 2 );
            $this->cache_driver->set( 'test.baz', 'hello!' );
            $this->cache_driver->set( 'no_delete', ':)' );

            $this->cache_driver->delete( 'test.*' );

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

            $this->cache_driver->set( 'test.foo', 3.14 );
            $this->cache_driver->set( 'test.bar', 2 );
            $this->cache_driver->set( 'test.baz', 'hello!' );
            $this->cache_driver->set( 'no_delete', ':)' );

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

            $this->cache_driver->deleteRegEx( '/test\\.ba./', TRUE );

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

            $this->cache_driver->set( 'test.foo', 3.14 );
            $this->cache_driver->set( 'test.bar', 2 );
            $this->cache_driver->set( 'test.baz', 'hello!' );
            $this->cache_driver->set( 'no_delete', ':)' );

            $this->cache_driver->deleteRegEx( '/test\\.[foo|bar|baz]/', TRUE );

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