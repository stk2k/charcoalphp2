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
	public function render( Exception $e );
}

