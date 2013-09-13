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

class Charcoal_List extends Charcoal_Collection
{
	private $_values;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $value = array() )
	{
		parent::__construct();

		if ( $value ){
			if ( is_array($value) ){
				$this->_values = $value;
			}
			else{
				_throw( new NonArrayException($value) );
			}
		}
		else{
			$this->_values = array();
		}
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

	/*
	 *	先頭を取得
	 */
	public function getHead()
	{
		$cnt = count( $this->_values );
		if ( $cnt > 0 ){
			return $this->_values[ 0 ];
		}
		return NULL;
	}

	/*
	 *	先頭か（foreach中）
	 */
	public function isFirst() 
	{ 
		$hasPrevious = prev($this->_values); 
		// now undo 
		if ($hasPrevious) { 
			next($this->_values); 
		} else { 
			reset($this->_values); 
		} 
		return !$hasPrevious; 
	} 

	/*
	 *	次の要素があるか（foreach中）
	 */
	public function hasNext() 
	{ 
		$hasNext = next($this->_values); 
		// now undo 
		if ($hasNext) { 
			prev($this->_values); 
		} else { 
			end($this->_values); 
		} 
		return $hasNext; 
	}

	/*
	 *	最後か（foreach中）
	 */
	public function isLast() 
	{ 
		$hasNext = next($this->_values); 
		// now undo 
		if ($hasNext) { 
			prev($this->_values); 
		} else { 
			end($this->_values); 
		} 
		return !$hasNext; 
	}

	/*
	 *	最後尾を取得
	 */
	public function getTail()
	{
		$cnt = count( $this->_values );
		if ( $cnt > 0 ){
			return $this->_values[ $cnt - 1 ];
		}
		return NULL;
	}


	/*
	 *	最後尾の要素を削除
	 */
	public function removeTail()
	{
		return array_pop( $this->_values );
	}

	/*
	 *	先頭の要素を削除
	 */
	public function removeHead()
	{
		return array_shift( $this->_values );
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

		return array_splice ( $this->_values, $index, $length );
	}

	/**
	 *	Check if the collection is empty
	 *	
	 *	@return bool        TRUE if this collection has no elements, FALSE otherwise
	 */
	public function isEmpty()
	{
		return count( $this->_values ) === 0;
	}

	/*
	 *	空か
	 */
	public function contains( Object $o )
	{
		foreach( $this->_values as $item ){
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
		$new_array_cnt = array_push( $this->_values, $item );

		return $new_array_cnt;
	}

	/*
	 *	最後尾に配列を追加
	 */
	public function addAll( Charcoal_Vector $items )
	{
		foreach( $items as $item ){
			array_push( $this->_values, $item );
		}

		return count($this->_values);
	}

	/*
	 *	値を取得
	 */
	public function getValue()
	{
		return $this->_values;
	}

	/*
	 *	値を設定
	 */
	protected function setValue( array $value )
	{
		$this->_values = $value;
	}

	/*
	 *	要素数を取得
	 */
	public function count()
	{
		return count( $this->_values );
	}

	/*
	 *	要素数を取得
	 */
	public function size()
	{
		return count( $this->_values );
	}

	/*
	 *	配列の先頭を取り出す
	 */
	public function shift()
	{
		return array_shift( $this->_values );
	}

	/*
	 *	配列の最後尾に追加する
	 */
	public function push()
	{
		return array_shift( $this->_values );
	}

	/*
	 *	コールバックを各要素に適用し、リストを生成する
	 */
	public function map( $callback )
	{
		$new_array = array_map( $callback, $this->_values );
		return new Charcoal_List( $new_array );
	}

	/*
	 *	配列化
	 */
	public function toArray()
	{
		return array_diff( $this->_values, array() );
	}

	/*
	 *	逆順にする
	 */
	public function reverse()
	{
		return new Charcoal_List( array_reverse( $this->_values ) );
	}

	/*
	 *	文字列で連結する
	 */
	public function join( $delimiter = ',', $with_type = FALSE, $max_size = 0 )
	{
		return Charcoal_System::implodeArray( $delimiter, $this->_values, $with_type, $max_size );
	}

	/*
	 *	文字列化
	 */
	public function toString()
	{
		return $this->join();
	}

}

