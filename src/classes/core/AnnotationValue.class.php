<?php
/**
* アノテーション値クラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_AnnotationValue extends Charcoal_Object
{
	private $_tbl_model;
	private $_name;
	private $_value;
	private $_params;

	/*
	 *	コンストラクタ
	 */
	public function __construct( Charcoal_ITableModel $tbl_model, Charcoal_String $name, Charcoal_String $value, Charcoal_Vector $params )
	{
		$this->_tbl_model = $tbl_model;
		$this->_name      = us($name);
		$this->_value     = us($value);
		$this->_params    = uv($params);
	}

	/*
	 *	アノテーション名を取得
	 */
	public function getName()
	{
		return $this->_name;
	}

	/*
	 *	アノテーション値を取得
	 */
	public function getValue()
	{
		return $this->_value;
	}

	/*
	 *	アノテーション値を設定
	 */
	public function setValue( Charcoal_String $value )
	{
		$this->_value = us($value);
	}

	/*
	 *	パラメータ値を取得
	 */
	public function getParameters()
	{
		return $this->_params;
	}

	/*
	 *	パラメータ値を取得
	 */
	public function getParameter( Charcoal_Integer $index, Charcoal_String $defaultValue = NULL )
	{
		$idx = ui( $index );
		return isset($this->_params[ $idx ]) ? $this->_params[ $idx ] : us($defaultValue);
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		$str  = "[table]" . $this->_tbl_model->getTableName();
		$str .= " [name]" . $this->_name . " [value]" . $this->_value;
		$str .= " [params]" . explode(",",$this->_params);
		return $str;
	}
}

