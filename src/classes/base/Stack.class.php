<?php
/**
* 汎用スタッククラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Stack extends Charcoal_Collection
{
	private $_values;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $value = array() )
	{
		parent::__construct();

		$this->_values = $value;
	}

	/*
	 *	先頭の要素を取得
	 */
	public function getHead()
	{
		$cnt = count($this->_values);
		if ( $cnt === 0 ){
			_throw( new EmptyStackException( $this ) );
		}

		return $this->_values[0];
	}

	/*
	 *	最後の要素を取得
	 */
	public function getTail()
	{
		$cnt = count($this->_values);
		if ( $cnt === 0 ){
			_throw( new EmptyStackException( $this ) );
		}
		$i = $cnt - 1;
		return isset($this->_values[$i]) ? $this->_values[$i] : NULL;
	}

	/**
	 *	Check if the collection is empty
	 *	
	 *	@return bool        TRUE if this collection has no elements, FALSE otherwise
	 */
	public function isEmpty()
	{
		return empty( $this->_values );
	}

	/*
	 *	要素数を取得
	 */
	public function count()
	{
		return count( $this->_values );
	}

	/*
	 *	全ての要素を削除
	 */
	public function clear()
	{
		$this->_values = array();
	}

	/*
	 *	要素を追加
	 */
	public function push( $item )
	{
//		Charcoal_ParamTrait::checkObject( 1, $item );

		$this->_values[] = $item;
	}

	/*
	 *	要素を取得
	 */
	public function pop()
	{
		$tail = array_pop( $this->_values );
		if ( !$tail ){
			_throw( new Charcoal_StackEmptyException( $this ) );
		}

		return $tail;
	}

	/**
	 *	Get all values with keys
	 *
	 * @return array
	 */
	public function getAll()
	{
		return $this->_values;
	}

	/*
	 *	Iteratorインタフェース:rewidの実装
	 */
	public function rewind() {
		reset($this->_values);
	}

	/*
	 *	Iteratorインタフェース:currentの実装
	 */
	public function current() {
		$var = current($this->_values);
		return $var;
	}

	/*
	 *	Iteratorインタフェース:keyの実装
	 */
	public function key() {
		$var = key($this->_values);
		return $var;
	}

	/*
	 *	Iteratorインタフェース:nextの実装
	 */
	public function next() {
		$var = next($this->_values);
		return $var;
	}

	/*
	 *	Iteratorインタフェース:validの実装
	 */
	public function valid() {
		$var = $this->current() !== false;
		return $var;
	}

}

