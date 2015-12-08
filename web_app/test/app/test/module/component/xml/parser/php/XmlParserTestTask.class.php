<?php
/**
* Test Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class TestPhpXmlElementHandler implements Charcoal_PhpXmlElementHandler
{
    /**
    *　will be callbacked when a XML element is found
    */
    public function onXmlElementEnd( PhpXmlElement $element )
    {
        echo "XML element ended: " . $element->getName() . PHP_EOL;
    }

    /**
    *　will be callbacked when a XML element is found
    */
    public function onXmlElementStart( PhpXmlElement $element )
    {
        echo "XML element started: " . $element->getName() . PHP_EOL;
    }
}

class XmlParserTestTask extends Charcoal_TestTask
{
    /**
     * check if action will be processed
     */
    public function isValidAction( $action )
    {
        switch( $action ){
        case "xml1":
        case "xml2":
        case "web1":
        case "web2":
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
        $request   = $context->getRequest();

        $action = us($action);

        // PhpXmlParser
        $parser = $context->getComponent( 'xmlparser@:xml:parser:php' );

        $config = new Charcoal_Config( $this->getSandbox()->getEnvironment() );

        $parser->configure( $config );

        switch( $action ){
        case "web1":

            $html = file_get_contents('http://charcoalphp.org');
            $html = file_get_contents('http://geitsuboo.blog.fc2.com/');

            $html = str_replace('euc-jp', 'UTF-8', $html);

            $parser->setElementHandler( new TestPhpXmlElementHandler() );
            $parser->setOutputEncoding( 'sjis-win' );

            $parser->parse( $html, true );

            return TRUE;

        case "xml1":

$xml1 = <<< XMLDATA1
<?xml version="1.0" encoding="UTF-8" ?>
<venture>
  <company>
    <name>Microsoft</name>
    <url>http://microsoft.com/</url>
  </company>

  <company>
    <name>Google</name>
    <url>http://google.com/</url>
  </company>
</venture>
XMLDATA1;

            echo "xml:$xml1" . PHP_EOL;

            $parser->setElementHandler( new TestPhpXmlElementHandler() );

            $parser->parse( $xml1, true );

            return TRUE;

        case "xml2":
            global $xml1;

            $tag = '';

            $parser->setRawElementHandler(
                    function( $p , $name , $attribs ) use(&$tag){
                        echo "[$name] start:" . print_r($attribs,true) . PHP_EOL;
                        $tag = $name;
                    },
                    function( $p , $name ){
                        echo "[$name] end" . PHP_EOL;
                    }
                );

            $parser->setRawCharacterDataHandler(
                    function( $p , $data ) use(&$tag){
                        echo "text data[$tag]: $data" . PHP_EOL;
                    }
                );

            $parser->parse( $xml1, true );

            return TRUE;
        }

        return FALSE;
    }

}

return __FILE__;