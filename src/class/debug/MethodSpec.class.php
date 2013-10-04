<?php
/**
* メソッド呼び出し履歴クラス
*
* PHP version 5
*
* @package    class.debug
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_MethodSpec extends Charcoal_Object
{
	private $_ref;
	private $_args;
	private $_args_cnt;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $class, $method )
	{
		parent::__construct();

		$ref = new ReflectionMethod( $class, $method );

		$this->_ref      = $ref;
		$this->_args     = $ref->getParameters();
		$this->_args_cnt = $ref->getNumberOfParameters();
	}

	/*
	 *	文字列化
	 */
	public function toString()
	{
		$ref      = $this->_ref;
		$class    = $ref->getDeclaringClass()->getName();
		$method   = $ref->getName();
		$args     = $this->_args;
		$args_cnt = $this->_args_cnt;

		$str = $class . '::' . $method . '(';
		foreach( $args as $p ){
			$class           = $p->getClass();
			$param_name      = $p->getName();
			$is_array        = $p->isArray();
			$reference       = $p->isPassedByReference();
			$default_exsits  = $p->isDefaultValueAvailable();
			$default_value   = $default_exsits ? $p->getDefaultValue() : '';

			$str .= ($class ? $class->getName() : NULL);
			$str .= ($is_array ? '[] ' : ' ') . ($reference ? '&amp;' : '');
			$str .= $param_name;
			if ( $default_exsits ){
				$str .= ' = ' . ($default_value ? $default_value : 'NULL');
			}
			if ( --$args_cnt > 0 ){
				$str .= ',';
			}
		}
		$str .= ')';

		return $str;
	}
}

