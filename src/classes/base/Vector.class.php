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

class Charcoal_Vector extends Charcoal_Collection implements ArrayAccess
{
	private $_values;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $value = array() )
	{
		parent::__construct();

		if ( $value ){
			if ( $value instanceof Charcoal_Vector ){
				$this->_values = $value->unbox();
			}
			else if ( is_array($value) ){
				$this->_values = $value;
			}
			else{
				_throw( new Charcoal_NonArrayException( $value ) );
			}
		}
		else{
			$this->_values = array();
		}
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

    /**
     *	unbox primitive value
     */
    public function unbox()
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
	 *	array_flip
	 */
	public function flip()
	{
		return array_flip( $this->_values );
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
	 *	要素値を取得
	 */
	public function get( Charcoal_Integer $index )
	{
		$index = $index->getValue();
		return isset($this->_values[$index]) ? $this->_values[$index] : NULL;
	}

	/*
	 *	要素値を更新
	 */
	public function set( Charcoal_Integer $index, $value )
	{
		$index = $index->getValue();
		$this->_values[$index] = $value;
	}

	/*
	 * プロパティの取得
	 */
	public function __get( $name )
	{
		$data =  isset($this->_values[ $name ]) ? $this->_values[ $name ] : NULL;
		return $data;
	}

	/*
	 * プロパティの設定
	 */
	public function __set( $name, $value )
	{
		$this->_values[ $name ] = $value;
	}

	/*
	 *	ArrayAccessインタフェース:offsetGetの実装
	 */
	public function offsetGet($offset)
	{
		if ( $offset instanceof Charcoal_Object ){
			$offset = us( $offset );
		}
		return isset($this->_values[ $offset ]) ? $this->_values[ $offset ] : NULL;
	}

	/*
	 *	ArrayAccessインタフェース:offsetSetの実装
	 */
	public function offsetSet($offset, $value)
	{
		if ( $offset instanceof Charcoal_Object ){
			$offset = us( $offset );
		}
		$this->_values[ $offset ] = $value;
	}

	/*
	 *	ArrayAccessインタフェース:offsetExistsの実装
	 */
	public function offsetExists($offset)
	{
		if ( $offset instanceof Charcoal_Object ){
			$offset = $offset->s();
		}
		return isset($this->_values[$offset]);
	}

	/*
	 *	ArrayAccessインタフェース:offsetUnsetの実装
	 */
	public function offsetUnset($offset)
	{
		unset($this->_values[$offset]);
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
	 *	キー一覧を取得
	 */
	public function keys()
	{
		return new Vector( array_keys( $this->_values ) );
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
	 *	要素を検索
	 */
	public function indexOf( Object $object, Charcoal_Integer $index = NULL )
	{
		if ( $index === NULL ){
			$index = 0;
		}
		$size = $this->size();
		for( $i=$index; $i < $size; $i++ ){
			$item = $this->_values[$i];
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
		return in_array( $value, $this->_values );
	}

	/*
	 *	コールバックを各要素に適用し、配列を生成する
	 */
	public function map( $callback )
	{
		$new_array = array_map( $callback, $this->_values );
		return new Vector( $new_array );
	}

	/*
	 *	配列化
	 */
	public function toArray()
	{
		return $this->_values;
	}

	/*
	 *	逆順にする
	 */
	public function reverse()
	{
		return new Charcoal_Vector( array_reverse( $this->_values ) );
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

