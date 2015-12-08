<?php
/**
* Logger interface
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

interface Charcoal_ILogger extends Charcoal_ICharcoalObject
{
    /*
     * write header message
     */
    public function writeHeader();

    /*
     * write footer message
     */
    public function writeFooter();

    /*
     * write one message
     */
    public function writeln( Charcoal_LogMessage $message );

    /**
     * destruct instance
     */
    public function terminate();

}

