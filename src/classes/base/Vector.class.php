<?php
/**
* 配列クラス
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Vector extends Charcoal_Primitive implements Iterator, ArrayAccess, Countable
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
				_throw( new Charcoal_NonArrayException($value) );
			}
		}
		else{
			$this->_value = array();
		}
	}

    /**
     *	unbox primitive value
     */
    public function unbox()
    {
        return $this->_value;
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
	 *	要素値を取得
	 */
	public function get( Charcoal_Integer $index )
	{
		$index = $index->getValue();
		return isset($this->_value[$index]) ? $this->_value[$index] : NULL;
	}

	/*
	 *	要素値を更新
	 */
	public function set( Charcoal_Integer $index, $value )
	{
		$index = $index->getValue();
		$this->_value[$index] = $value;
	}

	/*
	 * プロパティの取得
	 */
	public function __get( $name )
	{
		$data =  isset($this->_value[ $name ]) ? $this->_value[ $name ] : NULL;
		return $data;
	}

	/*
	 * プロパティの設定
	 */
	public function __set( $name, $value )
	{
		$this->_value[ $name ] = $value;
	}

	/*
	 *	ArrayAccessインタフェース:offsetGetの実装
	 */
	public function offsetGet($offset)
	{
		if ( $offset instanceof Charcoal_Object ){
			$offset = us( $offset );
		}
		return isset($this->_value[ $offset ]) ? $this->_value[ $offset ] : NULL;
	}

	/*
	 *	ArrayAccessインタフェース:offsetSetの実装
	 */
	public function offsetSet($offset, $value)
	{
		if ( $offset instanceof Charcoal_Object ){
			$offset = us( $offset );
		}
		$this->_value[ $offset ] = $value;
	}

	/*
	 *	ArrayAccessインタフェース:offsetExistsの実装
	 */
	public function offsetExists($offset)
	{
		if ( $offset instanceof Charcoal_Object ){
			$offset = $offset->s();
		}
		return isset($this->_value[$offset]);
	}

	/*
	 *	ArrayAccessインタフェース:offsetUnsetの実装
	 */
	public function offsetUnset($offset)
	{
		unset($this->_value[$offset]);
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
	 *	キー一覧を取得
	 */
	public function keys()
	{
		return new Vector( array_keys( $this->_value ) );
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
	 *	要素を検索
	 */
	public function indexOf( Object $object, Charcoal_Integer $index = NULL )
	{
		if ( $index === NULL ){
			$index = 0;
		}
		$size = $this->size();
		for( $i=$index; $i < $size; $i++ ){
			$item = $this->_value[$i];
			if ( $item instanceof Charcoal_Object ){
				if ( $item->equals($object) ){
					return $i;
				}
			}
		}

		return FALSE;
	}

	/*
	 *	check if contains a value
	 */
	public function contains( $value )
	{
		return in_array( $value, $this->_value );
	}

	/*
	 *	コールバックを各要素に適用し、配列を生成する
	 */
	public function map( $callback )
	{
		$new_array = array_map( $callback, $this->_value );
		return new Vector( $new_array );
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
		return new Charcoal_Vector( array_reverse( $this->_value ) );
	}

	/*
	 *	文字列で連結する
	 */
	public function join( Charcoal_String $delimiter = NULL, Charcoal_Boolean $with_type = NULL, Charcoal_Integer $max_size = NULL )
	{
		$with_type = $with_type ? ub($with_type) : FALSE;
		$max_size  = $max_size ? ui($max_size) : 0;

		$array      = $this->_value;
		$delimiter  = $delimiter ? us($delimiter) : ',';

		$implode    = Charcoal_System::implodeArray( $delimiter, $array, $with_type, $max_size );

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

