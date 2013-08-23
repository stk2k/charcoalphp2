<?php
/**
* Logger interface
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

interface Charcoal_ILogger extends Charcoal_ICharcoalObject
{
	/*
	 * teminate logger
	 */
	public function terminate();

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
}

return __FILE__;