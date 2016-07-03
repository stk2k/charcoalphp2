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

class TempFileTestTask extends Charcoal_TestTask
{
    /**
     * check if action will be processed
     *
     * @param string $action
     *
     * @return boolean
     */
    public function isValidAction( $action )
    {
        switch( $action ){
        case "create":
        case "get_contents":
        case "put_contents":
            return TRUE;
        }
        return FALSE;
    }

    /**
     * setup test
     *
     * @param string $action
     * @param Charcoal_IEventContext $context
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
     *
     * @param string $action
     * @param Charcoal_IEventContext $context
     */
    public function cleanUp( $action, $context )
    {
    }

    /**
     * get headers
     *
     * @return string
     */
    private function get_headers()
    {
        return implode( ",", headers_list() );
    }

    /**
     * execute tests
     *
     * @param string $action
     * @param Charcoal_IEventContext $context
     *
     * @return boolean
     */
    public function test( $action, $context )
    {
        $action = us($action);

        $this->setVerbose( true );

        // temp file component
        /** @var Charcoal_TempFileComponent $tf */
        $tf = $context->getComponent( 'temp_file@:charcoal:file' );

        switch( $action ){
        case "create":
            $file = $tf->create( "test" );

            $this->assertTrue( $file->exists() );
            $this->assertTrue( $file->canRead() );
            $this->assertEquals( "test", $file->getContents() );

            return TRUE;

        case "get_contents":
            $temp_file = new Charcoal_File( CHARCOAL_TMP_DIR . '/tmpfile.txt' );

            $temp_file->putContents( "test" );

            $tf->setFile( $temp_file );

            $this->assertEquals( "test", $tf->getContents() );

            return TRUE;


        case "put_contents":
            $temp_file = new Charcoal_File( CHARCOAL_TMP_DIR . '/tmpfile.txt' );

            $tf->setFile( $temp_file );
            $tf->putContents( "cat" );

            $this->assertTrue( $temp_file->exists() );
            $this->assertTrue( $temp_file->canRead() );
            $this->assertEquals( "cat", $temp_file->getContents() );

            return TRUE;
        }

        return FALSE;
    }

}

return __FILE__;