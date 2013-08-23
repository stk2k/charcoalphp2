<?php
/**
* 設定値を保持するクラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Config extends Charcoal_ConfigPropertySet
{
	/*
	 *    コンストラクタ
	 */
	public function __construct( array $data = NULL )
	{
		parent::__construct( $data );
	}

	/*
	 * 他の設定ファイルから設定値を上書きする
	 */
	public function import( Charcoal_ObjectPath $object_path, Charcoal_String $type_name )
	{
		Charcoal_ConfigLoader::loadConfig( $object_path, $type_name, $this );
	}

}
return __FILE__;