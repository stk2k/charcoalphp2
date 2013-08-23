<?php
/**
* 例外ハンドラ
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_IExceptionHandler extends Charcoal_ICharcoalObject
{
	/**
	 * フレームワーク例外ハンドラ
	 */
	public function handleFrameworkException( Charcoal_CharcoalException $e );

	/**
	 * 例外ハンドラ
	 */
	public function handleException( Exception $e );
}

return __FILE__;