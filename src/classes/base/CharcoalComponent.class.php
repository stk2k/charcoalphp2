<?php
/**
* フレームワークコンポーネント
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/


class Charcoal_CharcoalComponent extends Charcoal_CharcoalObject
{
	private $component_name;

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 *   get component name
	 *
	 * @return string          component name
	 */
	public function getComponentName()
	{
		return $this->component_name;
	}

	/**
	 *   set component name
	 *
	 * @param Charcoal_String $component_name          component name
	 */
	public function setComponentName( $component_name )
	{
//		Charcoal_ParamTrait::checkString( 1, $component_name );

		$this->component_name = $component_name;
	}
}

