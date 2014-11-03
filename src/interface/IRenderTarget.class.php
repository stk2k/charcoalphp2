<?php
/**
* Interface of rendering target
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2014 stk2k, sazysoft
*/
interface Charcoal_IRenderTarget
{

	/**
	 *	render buffer
	 *
	 * @param Charcoal_String|string $buffer    rendering data
	 */
	public function render( $buffer );

}

