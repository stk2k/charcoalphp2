<?php
/**
* アノテーション値クラス
*
* PHP version 5
*
* @package    class.core
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
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
		Charcoal_ParamTrait::validateString( 1, $name );
		Charcoal_ParamTrait::validateString( 2, $value );
		Charcoal_ParamTrait::validateVector( 3, $params );

		$this->name    = us($name);
		$this->value   = us($value);
		$this->params  = uv($params);
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

	/**
	 *	get parameter value
	 *
	 * @param Charcoal_Integer|int $index
	 * @param Charcoal_String|string $defaultValue
	 *
	 * @return string
	 */
	public function getParameter( $index, $defaultValue = NULL )
	{
		/** @var int $idx */
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
		$str  = " [name]" . $this->name . " [value]" . $this->value;
		$str .= " [params]" . implode(",",$this->params);
		return $str;
	}
}

