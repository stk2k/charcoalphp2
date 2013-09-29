<?php
/**
* response filter for html escaping
*
* PHP version 5
*
* @package    objects.response_filters
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_HtmlEscapeResponseFilter extends Charcoal_AbstractResponseFilter
{
	/**
	 * セットされた値を加工する
	 */
	public function apply( $value )
	{
		return Charcoal_System::escape( $value );
	}
}

