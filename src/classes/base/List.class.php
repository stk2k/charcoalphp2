<?php
/**
* リストクラス
*
* PHP version 5
*
* @package	base
* @author	 CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_List extends Charcoal_Object implements Iterator, Countable
{
	private $_value;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $value = array() )
	{
		parent::__construct();

		if ( $value ){
			if ( is_array($value) ){
				$this->_value = $value;
			}
			else{
				_throw( new NonArrayException($value) );
			}
		}
		else{
			$this->_value = array();
		}
	}

	/*
	 *	Iteratorインタフェース:rewidの実装
	 */
	public function rewind() {
		reset($this->_value);
	}

	/*
	 *	Iteratorインタフェース:currentの実装
	 */
	public function current() {
		$var = current($this->_value);
		return $var;
	}

	/*
	 *	Iteratorインタフェース:keyの実装
	 */
	public function key() {
		$var = key($this->_value);
		return $var;
	}

	/*
	 *	Iteratorインタフェース:nextの実装
	 */
	public function next() {
		$var = next($this->_value);
		return $var;
	}

	/*
	 *	Iteratorインタフェース:validの実装
	 */
	public function valid() {
		$var = $this->current() !== false;
		return $var;
	}

	/*
	 *	先頭を取得
	 */
	public function getHead()
	{
		$cnt = count( $this->_value );
		if ( $cnt > 0 ){
			return $this->_value[ 0 ];
		}
		return NULL;
	}

	/*
	 *	先頭か（foreach中）
	 */
	public function isFirst() 
	{ 
		$hasPrevious = prev($this->_value); 
		// now undo 
		if ($hasPrevious) { 
			next($this->_value); 
		} else { 
			reset($this->_value); 
		} 
		return !$hasPrevious; 
	} 

	/*
	 *	次の要素があるか（foreach中）
	 */
	public function hasNext() 
	{ 
		$hasNext = next($this->_value); 
		// now undo 
		if ($hasNext) { 
			prev($this->_value); 
		} else { 
			end($this->_value); 
		} 
		return $hasNext; 
	}

	/*
	 *	最後か（foreach中）
	 */
	public function isLast() 
	{ 
		$hasNext = next($this->_value); 
		// now undo 
		if ($hasNext) { 
			prev($this->_value); 
		} else { 
			end($this->_value); 
		} 
		return !$hasNext; 
	}

	/*
	 *	最後尾を取得
	 */
	public function getTail()
	{
		$cnt = count( $this->_value );
		if ( $cnt > 0 ){
			return $this->_value[ $cnt - 1 ];
		}
		return NULL;
	}


	/*
	 *	最後尾の要素を削除
	 */
	public function removeTail()
	{
		return array_pop( $this->_value );
	}

	/*
	 *	先頭の要素を削除
	 */
	public function removeHead()
	{
		return array_shift( $this->_value );
	}

	/*
	 *	任意の位置の要素を削除
	 */
	public function remove( Charcoal_Integer $index, Charcoal_Integer $length = NULL )
	{
		if ( !$length ){
			$length = i(1);
		}
		$index  = $index->getValue();
		$length = $length->getValue();

		return array_splice ( $this->_value, $index, $length );
	}

	/*
	 *	空か
	 */
	public function isEmpty()
	{
		return count( $this->_value ) === 0;
	}

	/*
	 *	空か
	 */
	public function contains( Object $o )
	{
		foreach( $this->_value as $item ){
			if ( $o->equals($item) ){
				return TRUE;
			}
		}
		return FALSE;
	}

	/*
	 *	最後尾に追加
	 */
	public function add( $item )
	{
		$new_array_cnt = array_push( $this->_value, $item );

		return $new_array_cnt;
	}

	/*
	 *	最後尾に配列を追加
	 */
	public function addAll( Charcoal_Vector $items )
	{
		foreach( $items as $item ){
			array_push( $this->_value, $item );
		}

		return count($this->_value);
	}

	/*
	 *	値を取得
	 */
	public function getValue()
	{
		return $this->_value;
	}

	/*
	 *	値を設定
	 */
	protected function setValue( array $value )
	{
		$this->_value = $value;
	}

	/*
	 *	要素数を取得
	 */
	public function count()
	{
		return count( $this->_value );
	}

	/*
	 *	要素数を取得
	 */
	public function size()
	{
		return count( $this->_value );
	}

	/*
	 *	配列の先頭を取り出す
	 */
	public function shift()
	{
		return array_shift( $this->_value );
	}

	/*
	 *	配列の最後尾に追加する
	 */
	public function push()
	{
		return array_shift( $this->_value );
	}

	/*
	 *	コールバックを各要素に適用し、リストを生成する
	 */
	public function map( $callback )
	{
		$new_array = array_map( $callback, $this->_value );
		return new Charcoal_List( $new_array );
	}

	/*
	 *	配列化
	 */
	public function toArray()
	{
		return array_diff( $this->_value, array() );
	}

	/*
	 *	逆順にする
	 */
	public function reverse()
	{
		return new Charcoal_List( array_reverse( $this->_value ) );
	}

	/*
	 *	文字列で連結する
	 */
	public function join( Charcoal_String $delimiter = NULL, Charcoal_Boolean $with_type = NULL, Charcoal_Integer $max_size = NULL )
	{
		$with_type = $with_type ? ub($with_type) : FALSE;
		$max_size  = $max_size ? ui($max_size) : 0;

		$array	  = $this->_value;
		$delimiter  = $delimiter ? us($delimiter) : ',';

		$implode	= Charcoal_System::implodeArray( $delimiter, $array, $with_type, $max_size );

		return us( $implode );
	}

	/*
	 *	文字列化
	 */
	public function toString()
	{
		return us( $this->join() );
	}

}
return __FILE__;
