<?php
/**
* DIコンテナに格納できるコンポーネントのインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

interface Charcoal_IComponent
{
	/**
	 *   get component name
	 */
	public function getComponentName();

	/**
	 *   set component name
	 */
	public function setComponentName( Charcoal_String $component_name );

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

return __FILE__;