<?php
/**
* オブジェクトを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_ICharcoalObject
{
	/**
	 *   オブジェクト名を取得
	 */
	public function getObjectName();

	/**
	 *   オブジェクトパスを取得
	 */
	public function getObjectPath();

	/**
	 *   オブジェクトパスを設定
	 */
	public function setObjectPath( Charcoal_ObjectPath $obj_path );

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config );
}

