<?php
/**
* response filter of strip_tags
*
* PHP version 5
*
* @package    objects.response_filters
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_StripTagsResponseFilter extends Charcoal_AbstractResponseFilter
{
	/**
	 * セットされた値を加工する
	 */
	public function apply( $value )
	{
		return Charcoal_System::stripTags( $value );
	}
}

