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
	private $_component_name;

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 *   get component name
	 */
	public function getComponentName()
	{
		return $this->_component_name;
	}

	/**
	 *   set component name
	 */
	public function setComponentName( Charcoal_String $component_name )
	{
		$this->_component_name = us($component_name);
	}
}

