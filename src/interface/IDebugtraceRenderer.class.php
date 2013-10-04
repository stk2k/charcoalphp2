<?php
/**
* Interface Of Debug Trace Renderer
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IDebugtraceRenderer extends Charcoal_ICharcoalObject
{

	/**
	 * Render debug trace
	 *
	 * @param Exception $e  exception to render
	 */
	public function render( $e );
}

