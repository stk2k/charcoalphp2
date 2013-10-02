<?php
/**
* 関数呼び出し履歴クラス
*
* PHP version 5
*
* @package    class.debug
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_FunctionSpec extends Charcoal_Object
{
	private $_available;
	private $_function;
	private $_args;
	private $_args_cnt;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $function )
	{
		parent::__construct();

		try{
			$ref = new ReflectionFunction($function);
			$this->_function  = $ref;
			$this->_args      = $ref->getParameters();
			$this->_args_cnt  = $ref->getNumberOfParameters();
			$this->_available = true;
		}
		catch( Exception $ex ){
			$this->_function  = $function;
			$this->_available = false;
		}
	}

	/*
	 *	文字列化
	 */
	public function toString()
	{
		if ( !$this->_available ){
			return $this->_function;
		}

		$function = $this->_function;
		$args     = $this->_args;
		$args_cnt = $this->_args_cnt;

		$str = $function->getName() . '(' . PHP_EOL;
		foreach( $args as $p ){
			$class           = $p->getClass();
			$param_name      = $p->getName();
			$is_array        = $p->isArray();
			$reference       = $p->isPassedByReference();
			$default_exsits  = $p->isDefaultValueAvailable();
			$default_value   = $default_exsits ? $p->getDefaultValue() : '';

			$str .= str_repeat('&nbsp;',3);
			$str .= ($class ? $class->getName() : NULL);
			$str .= ($is_array ? '[] ' : ' ') . ($reference ? '&amp;' : '');
			$str .= $param_name;
			if ( $default_exsits ){
				$str .= ' = ' . ($default_value ? $default_value : 'NULL');
			}
			if ( --$args_cnt > 0 ){
				$str .= ',';
			}
			$str .= PHP_EOL;
		}
		$str .= ')' . PHP_EOL;

		return $str;
	}
}

