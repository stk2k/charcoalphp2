<?php
/**
* リストクラス
*
* PHP version 5
*
* @package    class.base
* @author	 CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_List extends Charcoal_Collection
{
	/**
	 * Retrieve default value
	 *
	 * @return Charcoal_List        default value
	 */
	public static function defaultValue()
	{
		return new self();
	}

	/*
	 *	先頭を取得
	 */
	public function getHead()
	{
		$cnt = count( $this->values );
		if ( $cnt > 0 ){
			return $this->values[ 0 ];
		}
		return NULL;
	}

	/*
	 *	先頭か（foreach中）
	 */
	public function isFirst() 
	{ 
		$hasPrevious = prev($this->values); 
		// now undo 
		if ($hasPrevious) { 
			next($this->values); 
		} else { 
			reset($this->values); 
		} 
		return !$hasPrevious; 
	} 

	/*
	 *	次の要素があるか（foreach中）
	 */
	public function hasNext() 
	{ 
		$hasNext = next($this->values); 
		// now undo 
		if ($hasNext) { 
			prev($this->values); 
		} else { 
			end($this->values); 
		} 
		return $hasNext; 
	}

	/*
	 *	最後か（foreach中）
	 */
	public function isLast() 
	{ 
		$hasNext = next($this->values); 
		// now undo 
		if ($hasNext) { 
			prev($this->values); 
		} else { 
			end($this->values); 
		} 
		return !$hasNext; 
	}

	/*
	 *	最後尾を取得
	 */
	public function getTail()
	{
		$cnt = count( $this->values );
		if ( $cnt > 0 ){
			return $this->values[ $cnt - 1 ];
		}
		return NULL;
	}


	/*
	 *	最後尾の要素を削除
	 */
	public function removeTail()
	{
		return array_pop( $this->values );
	}

	/*
	 *	先頭の要素を削除
	 */
	public function removeHead()
	{
		return array_shift( $this->values );
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

		return array_splice ( $this->values, $index, $length );
	}

	/**
	 *	Check if the collection is empty
	 *	
	 *	@return bool        TRUE if this collection has no elements, FALSE otherwise
	 */
	public function isEmpty()
	{
		return count( $this->values ) === 0;
	}

	/*
	 *	空か
	 */
	public function contains( Object $o )
	{
		foreach( $this->values as $item ){
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
		$new_array_cnt = array_push( $this->values, $item );

		return $new_array_cnt;
	}

	/**
	 *  Add array data
	 *  
	 *  @param array $items        array data to add
	 */
	public function addAll( $items )
	{
//		Charcoal_ParamTrait::validateVector( 1, $items );

		foreach( $items as $item ){
			array_push( $this->values, $item );
		}
	}

	/*
	 *	値を取得
	 */
	public function getValue()
	{
		return $this->values;
	}

	/*
	 *	値を設定
	 */
	protected function setValue( array $value )
	{
		$this->values = $value;
	}

	/*
	 *	配列の先頭を取り出す
	 */
	public function shift()
	{
		return array_shift( $this->values );
	}

	/*
	 *	配列の最後尾に追加する
	 */
	public function push()
	{
		return array_shift( $this->values );
	}

	/*
	 *	コールバックを各要素に適用し、リストを生成する
	 */
	public function map( $callback )
	{
		$new_array = array_map( $callback, $this->values );
		return new Charcoal_List( $new_array );
	}

	/*
	 *	配列化
	 */
	public function toArray()
	{
		return array_diff( $this->values, array() );
	}

	/*
	 *	逆順にする
	 */
	public function reverse()
	{
		return new Charcoal_List( array_reverse( $this->values ) );
	}

	/*
	 *	文字列で連結する
	 */
	public function join( $delimiter = ',', $with_type = FALSE, $max_size = 0 )
	{
		return Charcoal_System::implodeArray( $delimiter, $this->values, $with_type, $max_size );
	}

	/*
	 *	文字列化
	 */
	public function toString()
	{
		return $this->join();
	}

}

