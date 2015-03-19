<?php
/**
* フレームワークコンポーネント
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
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
//		Charcoal_ParamTrait::validateString( 1, $component_name );

		$this->component_name = $component_name;
	}
}

