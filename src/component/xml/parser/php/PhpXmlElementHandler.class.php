<?php
/**
* xml element
*
* PHP version 5
*
* @package    component.xml.parser.php
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2012 CharcoalPHP Development Team
*/

interface Charcoal_PhpXmlElementHandler
{
    /**
    *　will be callbacked when a XML element is found
    */
    public function onXmlElementEnd( PhpXmlElement $element );

    /**
    *　will be callbacked when a XML element is found
    */
    public function onXmlElementStart( PhpXmlElement $element );
}

