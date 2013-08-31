<?php
/**
* HTMLレスポンスフィルタ
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_HtmlEscapeResponseFilter extends Charcoal_CharcoalObject implements Charcoal_IResponseFilter
{
	/**
	 * セットされた値を加工する
	 */
	public function apply( $value )
	{
		return Charcoal_System::escape( $value );
	}
}

