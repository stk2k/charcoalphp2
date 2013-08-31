<?php
/**
* フレームワークオブジェクトの基底クラス
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/


class Charcoal_CharcoalObject extends Charcoal_Object
{
	public $obj_name;
	public $obj_path;
	public $type_name;

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();

		$class_name = get_class($this);
		$this->obj_name = strtolower(preg_replace('/([a-z0-9])([A-Z])/', "$1_$2", $class_name));
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
	}

	/**
	 *  get object name
	 */
	public final function getObjectName()
	{
		return $this->obj_name;
	}

	/**
	 *  set object name
	 */
	public final function setObjectName( Charcoal_String $obj_name )
	{
		$this->obj_name = $obj_name;
	}

	/**
	 *   オブジェクトパスを取得
	 */
	public final function getObjectPath()
	{
		return $this->obj_path;
	}

	/**
	 *   オブジェクトパスを設定
	 */
	public final function setObjectPath( Charcoal_ObjectPath $obj_path )
	{
		$this->obj_path = $obj_path;
	}

	/**
	 *   タイプ名を取得
	 */
	public final function getTypeName()
	{
		return $this->type_name;
	}

	/**
	 *   タイプ名を設定
	 */
	public final function setTypeName( Charcoal_String $type_name )
	{
		$this->type_name = $type_name;
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		$path = $this->obj_path ? $this->obj_path->getObjectPathString() : $this->hash();
		$type = $this->type_name ? $this->type_name : '';
		return get_class($this) . '(' . $this->obj_name . '[' . $path . '/' . $type . '])';
	}

}

