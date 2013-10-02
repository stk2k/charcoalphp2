<?php
/**
* アノテーション値クラス
*
* PHP version 5
*
* @package    class.core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_AnnotationValue extends Charcoal_Object
{
	private $name;
	private $value;
	private $params;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $name, $value, $params )
	{
//		Charcoal_ParamTrait::checkString( 1, $name );
//		Charcoal_ParamTrait::checkString( 2, $value );
//		Charcoal_ParamTrait::checkVector( 3, $params );

		$this->name    = $name;
		$this->value   = $value;
		$this->params  = $params;
	}

	/*
	 *	アノテーション名を取得
	 */
	public function getName()
	{
		return $this->name;
	}

	/*
	 *	アノテーション値を取得
	 */
	public function getValue()
	{
		return $this->value;
	}

	/*
	 *	アノテーション値を設定
	 */
	public function setValue( Charcoal_String $value )
	{
		$this->value = $value;
	}

	/*
	 *	パラメータ値を取得
	 */
	public function getParameters()
	{
		return $this->params;
	}

	/*
	 *	パラメータ値を取得
	 */
	public function getParameter( Charcoal_Integer $index, Charcoal_String $defaultValue = NULL )
	{
		$idx = ui( $index );
		return isset($this->_params[ $idx ]) ? $this->_params[ $idx ] : $defaultValue;
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		$str .= " [name]" . $this->name . " [value]" . $this->value;
		$str .= " [params]" . explode(",",$this->params);
		return $str;
	}
}

