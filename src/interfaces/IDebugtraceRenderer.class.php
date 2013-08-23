<?php
/**
* Interface Of Debug Trace Renderer
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_IDebugtraceRenderer extends Charcoal_ICharcoalObject
{

	/**
	 * Render debug trace
	 *
	 * @param Charcoal_String $title  title
	 */
	public function render( Exception $e, Charcoal_String $title = NULL );

	/**
	 * Output
	 *
	 * @param Charcoal_String $title  title
	 */
	public function output( Exception $e, Charcoal_String $title = NULL );
}

return __FILE__;